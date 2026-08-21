<?php

namespace App\Http\Controllers;

use App\Domains\Odoo\Services\OdooAuthService;
use App\Models\Order;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    protected OdooAuthService $odooAuthService;
    protected array $productNameCache = [];

    public function __construct(OdooAuthService $odooAuthService)
    {
        $this->odooAuthService = $odooAuthService;
    }

    public function index(Request $request)
    {
        $request->validate([
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
        ]);

        $today = today()->toDateString();
        $from = $request->input('from');
        $to = $request->input('to');

        if (!$from && !$to) {
            $from = $today;
            $to = $today;
        } elseif ($from && !$to) {
            $to = $from;
        } elseif (!$from && $to) {
            $from = $to;
        }

        $vehicles = Vehicle::query()
            ->with([
                'driver' => function ($query) {
                    $query->withTrashed();
                },
                'driver.orders' => function ($query) use ($from, $to) {
                    $query->whereDate('delivery_date', '>=', $from)
                        ->whereDate('delivery_date', '<=', $to)
                        ->with(['customer', 'products']);
                },
            ])
            ->whereNotNull('driver_id')
            ->whereHas('driver')
            ->whereHas('driver.orders', function ($query) use ($from, $to) {
                $query->whereDate('delivery_date', '>=', $from)
                    ->whereDate('delivery_date', '<=', $to);
            })
            ->get();

        return view('inventory.index', compact('vehicles', 'from', 'to'));
    }

    public function show(Request $request, Vehicle $vehicle)
    {
        $request->validate([
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
        ]);

        $today = today()->toDateString();
        $from = $request->input('from');
        $to = $request->input('to');

        if (!$from && !$to) {
            $from = $today;
            $to = $today;
        } elseif ($from && !$to) {
            $to = $from;
        } elseif (!$from && $to) {
            $from = $to;
        }

        $vehicle->load([
            'driver' => function ($query) {
                $query->withTrashed();
            },
            'driver.orders' => function ($query) use ($from, $to) {
                $query->whereDate('delivery_date', '>=', $from)
                    ->whereDate('delivery_date', '<=', $to)
                    ->with(['customer', 'products']);
            },
        ]);
        
        $todayOrders = $vehicle->driver && $vehicle->driver->relationLoaded('orders')
            ? $vehicle->driver->orders
            : collect();
        $unpreparedOrders = $todayOrders->reject(fn ($order) => (bool) $order->is_prepared);
        $aggregatedProducts = $this->summarizeProductsForOrders($unpreparedOrders);

        return view('inventory.show', compact('vehicle', 'todayOrders', 'aggregatedProducts', 'from', 'to'));
    }

    public function markPrepared(Request $request, Order $order)
    {
        $order->is_prepared = true;
        $order->save();

        return redirect()->back()->with('success', __('dashboard.order_marked_prepared'));
    }

    public function summarizeProductsForOrders($orders): array
    {
        $grouped = [];

        if (empty($orders)) {
            return [];
        }

        foreach ($orders as $order) {
            if (empty($order->products)) {
                continue;
            }

            foreach ($order->products as $product) {
                if (empty($product->product_id)) {
                    continue;
                }

                $productId = (int) $product->product_id;

                if (!isset($grouped[$productId])) {
                    $productData = $this->resolveProductData($productId);
                    $grouped[$productId] = [
                        'product_id' => $productId,
                        'quantity' => 0,
                        'name' => $productData['name'],
                        'image_url' => $productData['image_url'],
                    ];
                }

                $grouped[$productId]['quantity'] += (int) $product->quantity;
            }
        }

        usort($grouped, function ($first, $second) {
            return $second['quantity'] <=> $first['quantity'];
        });

        return array_values($grouped);
    }

    protected function resolveProductData(int $productId): array
    {
        // Return cached data if available
        if (isset($this->productNameCache[$productId])) {
            return $this->productNameCache[$productId];
        }

        $data = [
            'name' => 'Product #' . $productId,
            'image_url' => null,
        ];

        try {
            $product = $this->odooAuthService->getProductByIdFromOdoo($productId);

            if (is_array($product)) {
                // Try different response formats for name
                if (!empty($product['name'])) {
                    $data['name'] = $product['name'];
                } elseif (!empty($product['data']['name'])) {
                    $data['name'] = $product['data']['name'];
                }

                // Extract image URL from various possible locations
                if (!empty($product['image_url'])) {
                    $data['image_url'] = $product['image_url'];
                } elseif (!empty($product['data']['image_url'])) {
                    $data['image_url'] = $product['data']['image_url'];
                } elseif (!empty($product['image'])) {
                    $data['image_url'] = $product['image'];
                } elseif (!empty($product['data']['image'])) {
                    $data['image_url'] = $product['data']['image'];
                }
            }
        } catch (\Throwable $exception) {
            report($exception);
        }

        // Cache the result
        $this->productNameCache[$productId] = $data;

        return $data;
    }

    protected function resolveProductName(int $productId): string
    {
        $data = $this->resolveProductData($productId);
        return $data['name'];
    }
}


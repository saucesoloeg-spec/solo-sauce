@extends('layouts.dashboard')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border shadow-xs mb-4">
            <div class="card-header border-bottom pb-3">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="font-weight-semibold text-lg mb-0">{{ __('dashboard.vehicle_inventory_details') }}</h6>
                        <p class="text-sm mb-0">{{ __('dashboard.todays_delivery_workload') }}</p>
                    </div>
                    <a href="{{ route('inventory.index', ['from' => $from ?? null, 'to' => $to ?? null]) }}" class="btn btn-sm btn-outline-secondary">{{ __('dashboard.back') }}</a>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="row mb-4">
                    <div class="col-md-4">
                        <h6 class="text-uppercase text-secondary mb-2">{{ __('dashboard.vehicle') }}</h6>
                        <p class="mb-1"><strong>{{ __('dashboard.brand') }}:</strong> {{ $vehicle->brand }}</p>
                        <p class="mb-1"><strong>{{ __('dashboard.model') }}:</strong> {{ $vehicle->model }}</p>
                        <p class="mb-1"><strong>{{ __('dashboard.color') }}:</strong> {{ $vehicle->color }}</p>
                        <p class="mb-1"><strong>{{ __('dashboard.license_plate') }}:</strong> {{ $vehicle->license_plate }}</p>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-uppercase text-secondary mb-2">{{ __('dashboard.driver') }}</h6>
                        <p class="mb-1"><strong>{{ __('dashboard.name') }}:</strong> {{ $vehicle->driver?->name ?? __('dashboard.unassigned') }}</p>
                        <p class="mb-1"><strong>{{ __('dashboard.email') }}:</strong> {{ $vehicle->driver?->email ?? '-' }}</p>
                        <p class="mb-1"><strong>{{ __('dashboard.phone') }}:</strong> {{ $vehicle->driver?->phone ?? '-' }}</p>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-uppercase text-secondary mb-2">{{ __('dashboard.today_orders') }}</h6>
                        <p class="mb-1"><strong>{{ __('dashboard.total_today_orders') }}:</strong> {{ $todayOrders->count() }}</p>
                    </div>
                </div>

                <form method="GET" action="{{ route('inventory.vehicles.show', ['vehicle' => $vehicle->id]) }}" class="row g-3 align-items-end mb-4">
                    <div class="col-md-4">
                        <label for="from" class="form-label text-sm">From</label>
                        <input type="date" id="from" name="from" class="form-control" value="{{ $from }}">
                    </div>
                    <div class="col-md-4">
                        <label for="to" class="form-label text-sm">To</label>
                        <input type="date" id="to" name="to" class="form-control" value="{{ $to }}">
                    </div>
                    <div class="col-md-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary mb-0">Filter</button>
                        <a href="{{ route('inventory.vehicles.show', ['vehicle' => $vehicle->id]) }}" class="btn btn-outline-secondary mb-0">Reset</a>
                    </div>
                </form>

                <h6 class="font-weight-semibold text-md mb-3">{{ __('dashboard.today_orders') }}</h6>
                <div class="table-responsive mb-5">
                    <table class="table align-items-center mb-0">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="text-secondary text-xs font-weight-semibold opacity-7 text-center">{{ __('dashboard.order_code') }}</th>
                                <th class="text-secondary text-xs font-weight-semibold opacity-7 text-center">{{ __('dashboard.customer') }}</th>
                                <th class="text-secondary text-xs font-weight-semibold opacity-7 text-center">{{ __('dashboard.delivery_status') }}</th>
                                <th class="text-secondary text-xs font-weight-semibold opacity-7 text-center">{{ __('dashboard.products') }}</th>
                                <th class="text-secondary text-xs font-weight-semibold opacity-7 text-center">{{ __('dashboard.prepared') }}</th>
                                <th class="text-secondary text-xs font-weight-semibold opacity-7 text-center">{{ __('dashboard.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($todayOrders as $order)
                                <tr class="text-center">
                                    <td>{{ $order->code }}</td>
                                    <td>{{ $order->customer?->name ?? $order->customer_name }}</td>
                                    <td>{{ ucfirst($order->delivery_status ?? 'pending') }}</td>
                                    <td>{{ $order->products->sum('quantity') }}</td>
                                    <td>
                                        @if($order->is_prepared)
                                            <span class="badge bg-gradient-success">{{ __('dashboard.prepared') }}</span>
                                        @else
                                            <span class="badge bg-gradient-secondary">{{ __('dashboard.not_prepared') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @unless($order->is_prepared)
                                            <form method="POST" action="{{ route('inventory.orders.mark-prepared', ['order' => $order->id]) }}">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success">{{ __('dashboard.mark_prepared') }}</button>
                                            </form>
                                        @endunless
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">{{ __('dashboard.no_sameday_orders') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>


                <h6 class="font-weight-semibold text-md mb-3">{{ __('dashboard.aggregated_products') }}</h6>
                <div class="table-responsive">
                    <table class="table align-items-center mb-0">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="text-secondary text-xs font-weight-semibold opacity-7 text-center" style="width: 80px;">{{ __('dashboard.product') }}</th>
                                <th class="text-secondary text-xs font-weight-semibold opacity-7 text-center">{{ __('dashboard.product') }}</th>
                                <th class="text-secondary text-xs font-weight-semibold opacity-7 text-center">{{ __('dashboard.total_quantity') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($aggregatedProducts as $item)
                                <tr class="text-center">
                                    <td>
                                        @if($item['image_url'])
                                            <img src="{{ $item['image_url'] }}" alt="{{ $item['name'] }}" style="max-width: 60px; max-height: 60px; object-fit: cover; border-radius: 4px;">
                                        @else
                                            <div style="width: 60px; height: 60px; background-color: #f0f0f0; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-box text-secondary"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>{{ $item['name'] }}</td>
                                    <td>{{ $item['quantity'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4">{{ __('dashboard.no_products_found') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

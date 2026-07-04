<?php

namespace Tests\Unit;

use App\Domains\Orders\Repositories\OrderRepository;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\SalesCustomer;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class OrderRepositoryTest extends TestCase
{
    public function test_update_order_delivery_falls_back_when_signature_storage_raises_chmod_error(): void
    {
        $order = new TestOrder();
        $order->id = 42;
        $order->signature = null;
        $order->delivery_status = null;

        $repository = new OrderRepository(new TestOrderModel($order), new SalesCustomer(), new OrderStatusHistory());

        $result = $repository->updateOrderDelivery([
            'id' => 42,
            'products' => [
                ['product_id' => 1, 'quantity' => 1],
            ],
            'signature' => new ThrowingUploadedFile(),
        ]);

        $this->assertTrue($result['success']);
        $this->assertNotNull($order->signature);
        $this->assertStringContainsString('signatures/', $order->signature);
        $this->assertTrue($order->saved);
    }
}

class TestOrder extends Order
{
    public $id;
    public $signature;
    public $delivery_status;
    public $saved = false;

    public function products()
    {
        return new class {
            public function where($column, $value)
            {
                return $this;
            }

            public function first()
            {
                return (object) ['quantity' => 2];
            }
        };
    }

    public function delivered()
    {
        return new class {
            public function exists()
            {
                return false;
            }

            public function create(array $data)
            {
                return true;
            }
        };
    }

    public function save(array $options = [])
    {
        $this->saved = true;

        return true;
    }
}

class TestOrderModel extends Order
{
    private $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function find($id, $columns = ['*'])
    {
        return $this->order;
    }
}

class ThrowingUploadedFile extends UploadedFile
{
    public function __construct()
    {
        parent::__construct(__FILE__, 'signature.png', 'image/png', null, true);
    }

    public function storeAs($path, $name, $disk = null)
    {
        throw new \RuntimeException('chmod(): Operation not permitted');
    }
}

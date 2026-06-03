<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'odoo_id',
        'sales_id',
        'customer_id',
        'customer_name',
        'customer_phone',
        'delivery_date',
        'amount_total',
        'amount_tax',
        'state',
        'payment_status',
        'driver_id',
        'delivery_status',
        'notes'
    ];

    protected $appends = ['order_type'];

    public function getOrderTypeAttribute()
    {
        $firstOrderId = self::where('customer_id', $this->customer_id)
            ->orderBy('created_at')
            ->value('id');

        return $this->id == $firstOrderId ? 'New Deal' : 'Reorder';
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function sales()
    {
        return $this->belongsTo(Sales::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class)->withTrashed();
    }

    public function products()
    {
        return $this->hasMany(OrderProduct::class);
    }

    public function statusHistory()
    {
        return $this->hasMany(OrderStatusHistory::class)->orderBy('created_at');
    }
}

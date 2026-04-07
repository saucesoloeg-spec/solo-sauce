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
        'sales_id',
        'customer_id',
        'customer_name',
        'customer_phone',
        'order_date',
        'amount_total',
        'amount_tax',
        'state',
        'payment_status',
        'driver_id',
        'delivery_status',
        'notes'
    ];

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
        return $this->belongsTo(Customer::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesCustomer extends Model
{
    use HasFactory;

    protected $fillable = [
        'sales_id',
        'customer_id',
        'visit_at',
        'order_id',
        'survey',
        'status',
        'notes',
    ];

    public function sales()
    {
        return $this->belongsTo(Sales::class, 'sales_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function answers()
    {
        return $this->hasMany(SurveyAnswer::class);
    }
    
}

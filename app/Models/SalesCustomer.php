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
        'status',
        'notes',
    ];
}

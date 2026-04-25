<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'sales_id',
        'name',
        'email',
        'phone',
        'address',
        'zone',
        'city',
        'country_odoo_id',
        'state_odoo_id',
        'city_odoo_id',
        'latitude',
        'longitude',
    ];

    public function sales() 
    {
        return $this->belongsTo(Sales::class, 'sales_id');
    }

    public function orders() 
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    public function visits()
    {
        return $this->hasMany(SalesCustomer::class);
    }

    public function answers()
    {
        return $this->hasMany(SurveyAnswer::class);
    }

}

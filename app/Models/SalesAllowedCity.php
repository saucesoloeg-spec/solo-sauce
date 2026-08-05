<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesAllowedCity extends Model
{
    protected $table = 'sales_allowed_cities';

    protected $fillable = [
        'sales_id',
        'city_odoo_id',
    ];

    public function sales()
    {
        return $this->belongsTo(Sales::class, 'sales_id');
    }
}

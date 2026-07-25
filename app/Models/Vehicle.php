<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'brand',
        'model',
        'color',
        'license_plate',
        'driver_id',
        'deputy_id',
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function deputy()
    {
        return $this->belongsTo(Deputy::class);
    }
    
}

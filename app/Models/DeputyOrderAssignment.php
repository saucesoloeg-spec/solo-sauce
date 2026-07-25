<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeputyOrderAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'deputy_id',
        'order_id',
        'assigned_at',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
    ];

    public function deputy()
    {
        return $this->belongsTo(Deputy::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}

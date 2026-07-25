<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\DeputyOrderAssignment;

class Deputy extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'notes',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    public function orderAssignments()
    {
        return $this->hasMany(DeputyOrderAssignment::class)->orderByDesc('created_at');
    }
}

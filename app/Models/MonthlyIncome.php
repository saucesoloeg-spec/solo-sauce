<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyIncome extends Model
{
    use HasFactory;

    protected $fillable = [
        'income',
        'collect_date',
        'active_companies',
        'active_packages',
        'confirmed_packages'
    ];
    
}

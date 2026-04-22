<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'sales_id',
        'sales_customer_id',
        'survey_id',
        'customer_id',
        'answer',
    ];

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    
}

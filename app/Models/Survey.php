<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'question_en',
        'question_ar',
        'type',
        'options',
        'is_required',
        'note',
        'order',
    ];

    protected $casts = [
        'options' => 'array',
    ];

    public function answers()
    {
        return $this->hasMany(SurveyAnswer::class);
    }
}

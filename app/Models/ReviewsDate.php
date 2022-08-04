<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewsDate extends Model
{
    use HasFactory;
    protected $fillable = [
        "month",
        "year",
        "frequency_id",
        "is_include_review"
    ];

    /** Relation To Frequency Period */
    public function frequencyPeriod()
    {
        return $this->belongsTo(FrequencyPeriod::class, 'frequency_id', 'id')
            ->select("id", "name", "slug");
    }
}


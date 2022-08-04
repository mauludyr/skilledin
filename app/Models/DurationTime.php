<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DurationTime extends Model
{
    use HasFactory;

    protected $fillable = [
        "period_time_id",
        "review_date_id",
        "duration_start",
        "duration_end",
        "performance_start",
        "performance_end",
        "reporting_year",
        "is_include"
    ];

    protected $appends = [
        "full_steatment"
    ];


    /** Relation to Frequency Time */
    public function reviewDate()
    {
        return $this->belongsTo(ReviewsDate::class, 'review_date_id', 'id')
            ->select("id", "month", "year", "frequency_id");
    }

    /** Relation to Period Time */
    public function periodTime()
    {
        return $this->belongsTo(PeriodTime::class, 'period_time_id', 'id')
            ->select("id", "period_name", "period_slug");
    }


    public function getFullSteatmentAttribute()
    {
        return "{$this->periodTime->period_name} {$this->reporting_year}";
    }

    /** Relation to Objective */
    public function objectives()
    {
        return $this->hasMany(Objective::class, 'duration_period_id', 'id');
    }
}

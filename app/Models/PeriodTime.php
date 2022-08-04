<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PeriodTime extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        "period_name",
        "period_slug"
    ];

    /** Relation to Duration Times */
    public function durations()
    {
        return $this->hasMany(DurationTime::class, 'period_time_id', 'id')
            ->with([
                "reviewDate",
            ]);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FrequencyPeriod extends Model
{
    use HasFactory;
    protected $fillable = [
        "name",
        "slug"
    ];


    /** Relation to Review Date */
    public function reviewDates()
    {
        return $this->hasMany(ReviewsDate::class, 'frequency_id', 'id');
    }
}

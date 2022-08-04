<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Objective extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "name",
        "description",
        "duration_period_id",
        "due_date",
        "owner_id",
        "objective_level_id",
        "parent_object_id",
        "is_save_draf",
        "objective_status",
        "is_new"
    ];


    protected $appends = [
        'total_progress_percentage',
    ];


    /** Relation to Duration Time */
    public function durationTime()
    {
        return $this->belongsTo(DurationTime::class, 'duration_period_id', 'id');
    }


    /** Relation to Key Result */
    public function keyResults()
    {
        return $this->hasMany(KeyResultObjective::class);
    }


    /** Relation to User as Owner */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id', 'id');
    }

    /** Relation to Objective Level */
    public function objectiveLevel()
    {
        return $this->belongsTo(ObjectiveLevel::class, 'objective_level_id', 'id');
    }

    /** Realtion recursive */
    public function objectiveParent()
    {
        return $this->belongsTo(Objective::class, 'parent_object_id', 'id');
    }

    public function objectChild()
    {
        return $this->hasMany(Objective::class, 'parent_object_id', 'id');
    }


    public function objectiveParentItems()
    {
        return $this->objectiveParent()->with('objectiveParent');
    }

    public function objectiveChildItems()
    {
        return $this->objectChild()->with([
            'objectChild',
            'keyResults',
        ]);
        // return $this->hasMany(Objective::class, 'parent_object_id', 'id')->with('objectChild');
    }

    public function getTotalProgressPercentageAttribute()
    {
        $onTrack = 0;
        $offTrack = 0;
        $atRisk = 0;
        $countTotalProgressKR = 0;
        $sumTotalProgressKR = 0;

        if($this->keyResults()->count() > 0) {
            $totalValueStatus = 0;
            $onTrack = $this->keyResults()->whereHas("status", function($q) {
                $q->where("status_slug", "on-track");
            })->count();

            $atRisk = $this->keyResults()->whereHas("status", function($q) {
                $q->where("status_slug", "at-risk");
            })->count();

            $offTrack = $this->keyResults()->whereHas("status", function($q) {
                $q->where("status_slug", "off-track");
            })->count();

            $countTotalProgressKR = $this->keyResults()->count();
            $sumTotalProgressKR = $this->keyResults()->sum('total_progress');
        } else {
            return 0;
        }


        if($this->objectiveChildItems()->count() > 0)
        {
            $childItems = $this->objectiveChildItems()->get();

            foreach ($childItems as $key => $value) {
                $countTotalProgressKR = $countTotalProgressKR + $value->keyResults()->count();

                if($value->keyResults()->count() > 0)
                {
                    $onTrack += $value->keyResults()->whereHas("status", function($q) {
                        $q->where("status_slug", "on-track");
                    })->count();

                    $atRisk += $value->keyResults()->whereHas("status", function($q) {
                        $q->where("status_slug", "at-risk");
                    })->count();

                    $offTrack += $value->keyResults()->whereHas("status", function($q) {
                        $q->where("status_slug", "off-track");
                    })->count();
                    
                    $sumTotalProgressKR = $sumTotalProgressKR + $value->keyResults()->sum('total_progress');
                    foreach ($value->keyResults()->get() as $key1 => $value1) {
                    }
                } else {
                    return 0;
                }

            }
        }


        $totalValueStatus = ($onTrack * 3) + ($atRisk * 2)+ ($offTrack * 1);
        $totalStatus = $onTrack + $atRisk + $offTrack;
        $total = ceil($sumTotalProgressKR / $countTotalProgressKR);

        if($totalStatus == 0) {
            return 0;
        }
        else {
            if ($total >= 100){
                return 100;
            } else {
                return $total;
            }
            // return ceil($totalStatus / $totalValueStatus);
        }

    }

    public function getObjectiveStatusAttribute()
    {
        if ($this->attributes['is_save_draf'] == true){
            return "DRAFT";
        } else if($this->attributes['is_new'] == true) {
            return "ON TRACK";
        }else {
            if($this->total_progress_percentage > 90)
            {
                return "ON TRACK";
            }
            else if($this->total_progress_percentage >= 50 && $this->total_progress_percentage <= 90)
            {
                return "AT RISK";
            }
            else if($this->total_progress_percentage < 90)
            {
                return "OFF TRACK";
            }
        }
    }

}


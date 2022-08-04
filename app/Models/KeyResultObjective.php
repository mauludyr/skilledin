<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;




class KeyResultObjective extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "title",
        "objective_id",
        "measure_id",
        "start_value",
        "target",
        "unit",
        "due_date",
        "is_draft",
        "owner_id",
        "old_progress_value",
        "last_progress_value",
        "last_status_id",
        "last_comment",
        "total_progress",
    ];

    /** Relation to Measure */
    public function measured()
    {
        return $this->belongsTo(Measure::class, 'measure_id', 'id');
    }

    /** Relation to Objective */
    public function objective()
    {
        return $this->belongsTo(Objective::class, 'objective_id', 'id');
    }

    /** Relation to User */
    public function executor()
    {
        return $this->belongsTo(User::class, 'owner_id', 'id');
    }

    /** Relation to Task Status  */
    public function status()
    {
        return $this->belongsTo(TaskStatus::class, 'last_status_id', 'id');
    }

    /** Relation to History Key Result */
    public function historyTrackResult()
    {
        return $this->hasMany(HistoryKeyResultUser::class, 'key_result_id', 'id');
    }

    /** Relation to Task */
    public function task()
    {
        return $this->hasOne(Task::class, 'key_result_id', 'id');
    }

    public function conversation()
    {
        return $this->hasOne(ConversationOkr::class, 'objective_id', 'id');
    }
}

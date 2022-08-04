<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryKeyResultUser extends Model
{
    use HasFactory;

    protected $fillable = [
        "key_result_id",
        "objective_id",
        "progress_value_before",
        "progress_value_after",
        "task_status_id",
        "comment",
        "user_id"
    ];

    /** Relation to Key Result User */
    public function keyResult()
    {
        return $this->belongsTo(KeyResultObjective::class, 'key_result_id', 'id');
    }

    /** Relation to Key Result User */
    public function taskStatus()
    {
        return $this->belongsTo(TaskStatus::class, 'task_status_id', 'id');
    }

    /** Relation to Key Result User */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        "task_name",
        "task_note",
        "duration",
        "start_date",
        "end_date",
        "is_starred",
        "is_completed",
        "created_by",
        "key_result_id",
        "status_id",
        "label_id",
        "delegate_id"
    ];

    /** Relation To Key Result */
    public function keyResult()
    {
        return $this->belongsTo(KeyResultObjective::class, 'key_result_id', 'id');
    }

    /** Relation To Status Task */
    public function status()
    {
        return $this->belongsTo(StatusTask::class, 'status_id', 'id');
    }

    /** Relation To Label Task */
    public function label()
    {
        return $this->belongsTo(TaskLabel::class, 'label_id', 'id');
    }

    /** Relation To Delegate */
    public function delegate()
    {
        return $this->belongsTo(User::class, 'delegate_id', 'id');
    }

    /** Relation To Conversation */
    public function conversation()
    {
        return $this->hasOne(ConversationTask::class, 'task_id', 'id');
    }
}

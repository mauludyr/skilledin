<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConversationTask extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        "conversation_id",
        "comment",
        "task_id"
    ];

    /** Relation to Conversation */
    public function conversation()
    {
        return $this->belongsTo(Conversation::class, 'conversation_id', 'id');
    }

    /** Relation to Task */
    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id', 'id');
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Conversation extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        "type",
        "accomplishment",
        "obstacle",
        "next_step",
        "step_date",
        "due_date",
        "is_ready",
        "status",
        "created_by"
    ];

    /** Relation To User */
    public function ConversationWith()
    {
        return $this->hasMany(ConversationUser::class, 'conversation_id', 'id');
    }

    /** Relation to Task */
    public function task()
    {
        return $this->hasMany(ConversationTask::class, 'conversation_id', 'id');
    }

    /** Relation to Okr */
    public function okr()
    {
        return $this->hasOne(ConversationOkr::class, 'conversation_id', 'id');
    }

    /** Relation to Step */
    public function step()
    {
        return $this->hasMany(ConversationStep::class, 'conversation_id', 'id');
    }

}

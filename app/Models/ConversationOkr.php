<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConversationOkr extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        "conversation_id",
        "comment",
        "objective_id"
    ];

    /** Relation to Conversation */
    public function conversation()
    {
        return $this->belongsTo(Conversation::class, 'conversation_id', 'id');
    }

    /** Relation to Objective */
    public function objective()
    {
        return $this->belongsTo(KeyResultObjective::class, 'objective_id', 'id');
    }
}

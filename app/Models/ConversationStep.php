<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConversationStep extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        "conversation_id",
        "step_name",
        "step_date",
        "is_ready"
    ];

    /** Relation to Objective */
    public function conversation()
    {
        return $this->belongsTo(Conversation::class, 'conversation_id', 'id');
    }

}

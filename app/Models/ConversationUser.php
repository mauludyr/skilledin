<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConversationUser extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        "conversation_id",
        "user_id",
        "comment"
    ];

    /** Relation to Conversation */
    public function conversation()
    {
        return $this->belongsTo(Conversation::class, 'conversation_id', 'id');
    }

    /** Relation to Objective */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}

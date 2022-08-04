<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LogParticularChange extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        "user_id",
        "field_name",
        "field_type",
        "old_value",
        "current_value",
        "attachment_name",
        "attachment_path",
        "status"
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}

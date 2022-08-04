<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeedbackQuestion extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "type",
        "description",
        "active_self",
        "active_direct_manager",
        "active_dotted_line_manager",
        "active_peers",
        "active_reverse_review"
    ];
}

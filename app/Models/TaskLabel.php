<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaskLabel extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        "label_name", 
        "label_slug"
    ];

    /** Relation to Task */
    public function task()
    {
        return $this->hasOne(Task::class, 'label_id', 'id');
    }
}

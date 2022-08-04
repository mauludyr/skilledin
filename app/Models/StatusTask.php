<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StatusTask extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        "status_name",
        "status_slug"
    ];

    /** Relation to Task */
    public function task()
    {
        return $this->hasOne(Task::class, 'status_id', 'id');
    }
}

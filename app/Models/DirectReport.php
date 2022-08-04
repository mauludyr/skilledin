<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DirectReport extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        "user_id",
        "manager_id",
        "dotline_manager_id"
    ];

    /** Relation of Direct Report To Manager*/
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id', 'id');
    }


    /** Relation of Direct Report To Manager*/
    public function dotlineManager()
    {
        return $this->belongsTo(User::class, 'dotline_manager_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }


}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ObjectiveLevel extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'level_name',
        'level_slug',
        'level_value',
        'is_active'
    ];


    /** Relation to Objectives */
    public function objectives()
    {
        return $this->hasMany(Objective::class, 'objective_level_id', 'id');
    }
}

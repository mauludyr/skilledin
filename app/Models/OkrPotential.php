<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OkrPotential extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'potential_name',
        'potential_slug',
        'potential_value',
        'is_active'
    ];
}

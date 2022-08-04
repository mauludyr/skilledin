<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CombineFieldSetting extends Model
{
    use HasFactory;
    protected $fillable = [
        "field_setting_id",
        "label_name",
        "is_public",
    ];
}

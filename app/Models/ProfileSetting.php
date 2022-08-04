<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ProfileSetting extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "name",
        "slug",
    ];


    /** Relation to Profile Field Settings */
    public function profileFieldSetting()
    {
        return $this->hasMany(ProfileFieldSetting::class, 'profile_setting_id', 'id');
    }

}

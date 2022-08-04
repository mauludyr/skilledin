<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProfileFieldSetting extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        "field_name",
        "field_slug",
        "profile_setting_id"
    ];

    /** Relation To Profile Setting */
    public function profileSetting()
    {
        return $this->belongsTo(ProfileSetting::class, 'profile_setting_id', 'id');
    }

}

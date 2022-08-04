<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserCustomField extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        "user_id",
        "custom_field_id",
        "value",
        "field_setting_id"
    ];

    /** Relation to User */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /** Relation to Custom Field */
    public function customField()
    {
        return $this->belongsTo(CustomField::class, 'custom_field_id', 'id');
    }

    /** Relation to Custom Field Setting */
    public function fieldSetting()
    {
        return $this->belongsTo(CustomFieldSetting::class, 'field_setting_id', 'id');
    }
}


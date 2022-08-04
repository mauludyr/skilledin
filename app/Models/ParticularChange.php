<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParticularChange extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "field_name",
        "field_type",
        "old_value",
        "current_value",
        "attachment_name",
        "attachment_path"
    ];

    protected $appends = [
        'old_value',
    ];

    public function getOldValueAttribute()
    {
        $profile = Profile::with(['nationality','location'])->where('user_id', Auth()->user()->id)->first();
        $employment = Employment::with('jobPosition')->where('user_id', Auth()->user()->id)->first();

        if ($this->attributes['field_name'] == 'personal_email'){
            return $profile->personal_email;
        } else if ($this->attributes['field_name'] == 'first_name'){
            return $profile->first_name;
        } else if ($this->attributes['field_name'] == 'middle_name'){
            return $profile->middle_name;
        } else if ($this->attributes['field_name'] == 'last_name'){
            return $profile->last_name;
        } else if ($this->attributes['field_name'] == 'role_id'){
            return $employment->jobPosition->job_name;
        } else if ($this->attributes['field_name'] == 'birthday'){
            return $profile->birthday;
        } else if ($this->attributes['field_name'] == 'address'){
            return $profile->address;
        } else if ($this->attributes['field_name'] == 'personal_mobile'){
            return $profile->phone_number;
        } else if ($this->attributes['field_name'] == 'emergency_contact_name'){
            return $profile->emergency_contact_name;
        } else if ($this->attributes['field_name'] == 'emergency_contact_number'){
            return $profile->emergency_contact_number;
        } else if ($this->attributes['field_name'] == 'start_date'){
            return $profile->date_joined;
        } else if ($this->attributes['field_name'] == 'nationality'){
            return $profile->nationality->nationality_name;
        } else if ($this->attributes['field_name'] == 'location'){
            return $profile->location->location_name;
        } else if ($this->attributes['field_name'] == 'salary'){
            return $employment->salary;
        } else {
            return null;
        }
    }
}

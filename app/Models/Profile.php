<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class Profile extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    
    protected $fillable = [
        "user_id",
        "first_name",
        "last_name",
        "middle_name",
        "birthday",
        "pronouns",
        "superpower",
        "address",
        "phone_number",
        "personal_email",
        "emergency_contact_name",
        "emergency_contact_number",
        "date_joined",
        "image_filename",
        "image_filepath",
        "nationality_id",
        "location_id",
        "location_name"
    ];


    public function getDateJoinedAttribute()
    {
        if(!empty($this->attributes['date_joined']) && $this->attributes['date_joined'] != null)
        {
            return Carbon::parse($this->attributes['date_joined'])->format('d M Y');
        }
        return null;
    }

    public function getBirthdayAttribute()
    {
        if(!empty($this->attributes['birthday']) && $this->attributes['birthday'] != null)
        {
            return Carbon::parse($this->attributes['birthday'])->format('d M Y');
        }
        return null;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /** Relatiton to Nationality */
    public function nationality()
    {
        return $this->belongsTo(Nationality::class, 'nationality_id', 'id');
    }

    /** Relatiton to Location */
    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id', 'id');
    }

    public function getTableColumns() {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
}

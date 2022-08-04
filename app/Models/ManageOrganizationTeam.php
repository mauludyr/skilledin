<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManageOrganizationTeam extends Model
{
    use HasFactory;
    protected $fillable = [
        "user_id",
        "grade_id",
        "teams"
    ];

    protected $appends = [
        "list_teams"
    ];


    /** Relation to User */
    public function humanResource()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /** Relation to Grade */
    public function grade()
    {
        return $this->belongsTo(Grade::class, 'grade_id', 'id');
    }


    public function getListTeamsAttribute()
    {
        if($this->attributes["teams"] != null && !empty($this->attributes["teams"]))
        {
            $teams = json_decode($this->attributes["teams"], true);
            return OrganizationTeam::whereIn("id", $teams)->get();
        }
        return [];
    }
}


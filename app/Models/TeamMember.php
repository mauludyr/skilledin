<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    use HasFactory;
    protected $fillable = ["organization_team_id", "member_id"];

    /** Relation To Organization Team */
    public function organizationTeam()
    {
        return $this->belongsTo(OrganizationTeam::class, 'organization_team_id', 'id');
    }

    /** Relation to User */
    public function user()
    {
        return $this->belongsTo(User::class, 'member_id', 'id');
    }
}

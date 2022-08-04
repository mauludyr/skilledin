<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrganizationTeam extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        "team_name",
        "manager_team_id",
        "parent_team_id",
    ];

    protected $hidden = ["created_at", "udpated_at"];


    /** Relation To User */
    public function managerTeam()
    {
        return $this->belongsTo(User::class, 'manager_team_id', 'id');
    }


    /** Relation to Team Member*/
    public function members()
    {
        return $this->hasMany(TeamMember::class, 'organization_team_id', 'id')->select("id", "organization_team_id", "member_id");
    }

    /** Relation to Team Business Partner */
    public function businessPartners()
    {
        return $this->hasMany(TeamBusinessPartner::class, 'organization_team_id', 'id');
    }


    /** Realtion recursive */
    public function parent()
    {
        return $this->belongsTo(OrganizationTeam::class, 'parent_team_id', 'id');
    }

    public function children()
    {
        return $this->hasMany(OrganizationTeam::class, 'parent_team_id', 'id');
    }


    public function parentTeam()
    {
        return $this->parent()->with('parent');
    }

    public function listTeams()
    {
        return $this->children()->with(['children']);
    }



}

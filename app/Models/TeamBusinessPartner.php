<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamBusinessPartner extends Model
{
    use HasFactory;

    protected $fillable = ["organization_team_id", "partner_id"];

    /** Relation To Organization Team  */
    public function organizationTeam()
    {
        return $this->belongsTo(OrganizationTeam::class, 'organization_team_id', 'id');
    }

    /** Realtion To User */
    public function user()
    {
        return $this->belongsTo(User::class, 'partner_id', 'id');
    }
}

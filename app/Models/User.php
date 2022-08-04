<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_active',
        'status_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = [
        "organization_team_name"
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function verify()
    {
        return $this->hasOne(UserVerify::class, 'user_id', 'id');
    }

    /** Relation to System Status  */
    public function systemStatus()
    {
        return $this->belongsTo(SystemStatus::class, 'status_id', 'id');
    }

    /** Relation to Profile (Has one) */
    public function profile()
    {
        return $this->hasOne(Profile::class, 'user_id', 'id');
    }


    /** Relation to Employment (Has one) */
    public function employment()
    {
        return $this->hasOne(Employment::class, 'user_id', 'id');
    }


    /** Relation to Direct Report */
    public function directReport()
    {
        return $this->hasMany(DirectReport::class, 'user_id', 'id');
    }

    public function employee()
    {
        return $this->hasMany(DirectReport::class, 'manager_id', 'id');
    }

    /** Relation To member team*/
    public function teamMember()
    {
        return $this->hasOne(TeamMember::class, 'member_id', 'id')->with(["organizationTeam"]);
    }

    /** Relation To member team*/
    public function teamBusiness()
    {
        return $this->hasOne(TeamBusinessPartner::class, 'partner_id', 'id')->with(["organizationTeam"]);
    }

    /** Relation To History Key Result User team*/
    public function historyKeyResultUser()
    {
        return $this->hasOne(HistoryKeyResultUser::class, 'user_id', 'id');
    }

    public function getOrganizationTeamNameAttribute()
    {
        $member = $this->teamMember()->first();
        $partner = $this->teamBusiness()->first();

        if($member != null && $partner != null) {
            if($member->organizationTeam->team_name == $partner->organizationTeam->team_name) {
                return $partner->organizationTeam->team_name;
            }
            else {
                return "{$partner->organizationTeam->team_name} And {$member->organizationTeam->team_name}";
            }
        }
        else {
            if($member != null) {
                return $member->organizationTeam->team_name;
            }
            else if($partner != null) {
                return $partner->organizationTeam->team_name;
            }
            else {
                return null;
            }
        }
   }

   /** Relation to Task */
    public function task()
    {
        return $this->hasOne(Task::class, 'delegate_id', 'id');
    }

    /** Relation to Profile (Has one) */
    public function logParticular()
    {
        return $this->hasOne(LogParticularChange::class, 'user_id', 'id');
    }

    /** Relation to Task */
    public function conversation()
    {
        return $this->hasOne(ConversationUser::class, 'user_id', 'id');
    }
}

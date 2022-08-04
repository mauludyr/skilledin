<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSocialite extends Model
{
    use HasFactory;
    protected $fillable = [
        "user_id",
        "socialite_id",
        "socialite_firstname",
        "socialite_lastname",
        "socialite_email",
        "socialite_phone",
        "socialite_image",
        "provider_name",
        "access_token",
        "expires_in"
    ];

    /** Relation To User */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}

<?php

namespace App\Helpers;
use Illuminate\Support\Facades\Http;

class Linkedin
{

    private $clientId;
    private $clientSecret;
    private $urlCallback;
    private $scope;
    private $state;




    public function __construct()
    {
        $this->clientId = env("LINKEDIN_CLIENT_ID");
        $this->clientSecret = env("LINKEDIN_CLIENT_SECRET");
        $this->urlCallback = env("LINKEDIN_CALLBACK_URL");
        $this->scope = "r_liteprofile%20r_emailaddress%20w_member_social";
        $this->state = "DCEeFWf45A53sdfKef424";
    }

    public function getAccessToken($code)
    {

        $url = "https://www.linkedin.com/oauth/v2/accessToken?grant_type=authorization_code&code={$code}&redirect_uri={$this->urlCallback}&client_id={$this->clientId}&client_secret={$this->clientSecret}";
        return Http::withHeaders([
            "Content-Type" => "application/x-www-form-urlencoded",
        ])->post($url);
    }

    public function getUserProfile($accessToken)
    {
        $url = "https://api.linkedin.com/v2/me?projection=(id,localizedFirstName,localizedLastName,profilePicture(displayImage~digitalmediaAsset:playableStreams))";

        return Http::withHeaders([
            "Authorization" => "Bearer {$accessToken}",
        ])->get($url);

    }

    public function getEmailProfile($accessToken)
    {
        $url = "https://api.linkedin.com/v2/clientAwareMemberHandles?q=members&projection=(elements*(primary,type,handle~))";
        return Http::withHeaders([
            "Authorization" => "Bearer {$accessToken}",
        ])->get($url);
    }


}

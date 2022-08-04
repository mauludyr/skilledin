<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Linkedin;
use GuzzleHttp\Client;
use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\UserSocialite;
use App\Traits\ResponseAPI;
use App\Traits\UserManagement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;
class LinkedinController extends Controller
{
    use ResponseAPI, UserManagement;
    private $linkedin;

    public function __construct()
    {
        $this->linkedin = new Linkedin();
    }

    public function getCallback()
    {
        $data = request()->all();
        Log::info(json_encode($data));
    }

    private function getProfiles($access_token)
    {

        $responseProfile = $this->linkedin->getUserProfile($access_token);
        $responseEmail = $this->linkedin->getEmailProfile($access_token);

        if($responseProfile->status() === 200 && $responseEmail->status() === 200)
        {

            return [
                "success" => true,
                "profile" => json_decode($responseProfile->body(), true),
                "email" => json_decode($responseEmail->body(), true)
            ];
        }
        else {
            return [
                "success" => false,
                "profile" => null,
                "email" => null
            ];
        }

    }

    private function genLinkedUserDataArray($user, $token, $profile, $email)
    {
        $data = [];
        $data["user_id"] = $user->id;
        $data["socialite_id"] =  $profile["id"];
        $data["socialite_firstname"] = $profile["localizedFirstName"];
        $data["socialite_lastname"] = $profile["localizedLastName"];
        $data["socialite_phone"] = "";

        if(count($email['elements']) > 0)
        {
            for ($i=0; $i < count($email["elements"]); $i++) {
                $type = $email["elements"][$i]["type"];
                switch (strtoupper($type)) {
                    case "EMAIL":
                        $data["socialite_email"] = $email["elements"][$i]["handle~"]["emailAddress"];
                        break;

                    case "PHONE":
                        $data["socialite_phone"] = $email["elements"][$i]["handle~"]["phoneNumber"]["number"];
                        break;

                    default:
                        break;
                }
            }
        }
        else
        {
            $data["socialite_email"] = "";
            $data["socialite_phone"] = "";
        }

        $urlImage = "";
        if(isset($profile["profilePicture"]))
        {
            $displayImage = $profile["profilePicture"]["displayImage~"];

            if(count($displayImage["elements"]) > 0)
            {
                $urlImage = $displayImage["elements"][0]["identifiers"][0]["identifier"];
            }
        }

        $data["socialite_image"] = $urlImage;
        $data["provider_name"] = "linkedin";
        $data["access_token"] = $token->access_token;
        $data["expires_in"] = $token->expires_in;
        return $data;
    }

    private function updateProfile($user, $socialite)
    {
        $profile = Profile::where('user_id', $user->id)->first();

        // if($socialite->socialite_firstname != null && !empty($socialite->socialite_firstname ))
        // {
        //     $profile->first_name = $socialite->socialite_firstname;
        // }

        // if($socialite->socialite_lastname != null && !empty($socialite->socialite_lastname ))
        // {
        //     $profile->middle_name = "";
        //     $profile->last_name = $socialite->socialite_lastname;
        // }


        if($socialite->socialite_phone != null && !empty($socialite->socialite_phone ))
        {
            $profile->phone_number = $socialite->socialite_phone;
        }

        if($socialite->socialite_image != null && !empty($socialite->socialite_image ))
        {
            $profile->image_filepath = $socialite->socialite_image;
        }

        if($socialite->socialite_email != null && !empty($socialite->socialite_email ))
        {
            $profile->personal_email = $socialite->socialite_email;
        }

        $profile->save();

        if(!$profile) {
            return $this->errorResponse("Failed to update user profile", 400);
        }


        $user->name = $this->combineToFullname($profile->first_name, $profile->middle_name, $profile->last_name);
        $user->save();
    }


    public function getLinkedInformation(Request $request)
    {
        $user = $request->user();

        if(!$request->code || empty($request->code))
        {
            return $this->errorResponse("Get authorization code failed", 400);
        }

        $responsesAuth = $this->linkedin->getAccessToken($request->code);

        if($responsesAuth->status() === 200)
        {
            $objectAccessToken = $responsesAuth->object();
            $responses = $this->getProfiles($objectAccessToken->access_token);

            if($responses["success"] == true)
            {
                $data = $this->genLinkedUserDataArray(
                    $user,
                    $objectAccessToken,
                    $responses["profile"],
                    $responses["email"]
                );

                $findSocialite = UserSocialite::where('user_id', $user->id)->first();
                if(!$findSocialite)
                {
                    // Buat Baru
                    $findSocialite = UserSocialite::create([
                        "user_id" => $data["user_id"],
                        "socialite_id" => $data["socialite_id"],
                        "socialite_firstname" => $data["socialite_firstname"],
                        "socialite_lastname" => $data["socialite_lastname"],
                        "socialite_email" => $data["socialite_email"],
                        "socialite_phone" => $data["socialite_phone"],
                        "socialite_image" => $data["socialite_image"],
                        "provider_name" => $data["provider_name"],
                        "access_token" => $data["access_token"],
                        "expires_in" => $data["expires_in"]
                    ]);

                    if(!$findSocialite) {
                        return $this->errorResponse("Failed get user profile from linkedin", 400);
                    }
                }
                else
                {
                    // Update
                    $findSocialite->user_id = $data["user_id"];
                    $findSocialite->socialite_id = $data["socialite_id"];
                    $findSocialite->socialite_firstname =  $data["socialite_firstname"];
                    $findSocialite->socialite_lastname =  $data["socialite_lastname"];
                    $findSocialite->socialite_email = $data["socialite_email"];
                    $findSocialite->socialite_phone = $data["socialite_phone"];
                    $findSocialite->socialite_image = $data["socialite_image"];
                    $findSocialite->provider_name = $data["provider_name"];
                    $findSocialite->access_token = $data["access_token"];
                    $findSocialite->expires_in = $data["expires_in"];
                    if(!$findSocialite->save()) {
                        return $this->errorResponse("Failed get user profile from linkedin", 400);
                    }
                }

                if($request->status == true) {
                    $this->updateProfile($user, $findSocialite);
                }

                return $this->successResponse("Update profile user with account linkedin", $findSocialite);

            }
            else
            {
                return $this->errorResponse("Failed to get user profile", 400);
            }
        }
        else
        {
            return $this->errorResponse("Failed to get access token", 400);
        }

    }

}

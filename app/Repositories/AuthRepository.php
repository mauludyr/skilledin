<?php

namespace App\Repositories;

use App\Mail\ResetPassword;
use App\Interfaces\AuthInterface;
use App\Models\SystemStatus;
use App\Models\User;
use App\Models\UserVerify;
use App\Traits\ResponseAPI;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthRepository implements AuthInterface
{
    use ResponseAPI;

    // User Login
    public function userLogin(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|email',
                'password' => 'required'
            ],
            [
                'email.required' => 'The :attribute field can not be blank value.',
                'password.required' => 'The :attribute field can not be blank value.'
            ]
        );

        if ($validator->fails())
        {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        $user = User::where('email', $request->email)->first();

        if(!$user)
        {
            return $this->errorResponse("Your email account is incorrect or not recognized, please contact your Human Resources department", 400);
        }


        if(!Hash::check($request->password, $user->password)) {
            return $this->errorResponse("Your password is incorrect, please try again, please click on Forgot Password? If your forgot your password", 400);
        }


        if($user->systemStatus->slug != 'activated') {
            return $this->errorResponse("Your email account is incorrect or not recognized, please contact your Human Resources department", 400);
        }

        $user->is_active = true;
        $user->save();

        $token = $user->createToken('auth_token')->plainTextToken;
        $employment = $user->employment()->with([
            'jobPosition' => function($q) {
                $q->select('id', 'job_name', 'job_slug');
            },
            'grade' => function($q) {
                $q->select('id','grade_name', 'grade_slug');
            },
            'employmentType' => function($q) {
                $q->select('id','emp_type_name', 'emp_type_slug');
            }
        ])->first();

        return $this->successResponse("Login Success", [
            "id" => $user->id,
            "name" => $user->name,
            "email" => $user->email,
            "access_token" => $token,
            "token_type" => "Bearer",
            "role" => $user->getRoleNames()[0],
            "permissions" => $user->getAllPermissions()->map(function($permissions) {
                return collect($permissions)->only(['id', 'name'])->all();
            }),
            "organization_team_name" => $user->organization_team_name,
            "systemStatus" => $user->systemStatus()->first()->setHidden([
                "created_at", "updated_at", "deleted_at", "slug"
            ]),
            "profile" => $user->profile()->with([
                'nationality' => function($q) {
                    $q->select('id', 'nationality_name', 'nationality_code');
                },
                'location'  => function($q) {
                    $q->select('id', 'location_name', 'location_code');
                },
            ])->first(),
            "employment" => [
                "id" => $employment->id ?? null,
                "salary" => $employment->salary ?? null,
                "job_position" => $employment->jobPosition ?? null,
                "grade" => $employment->grade ?? null,
                "employment_type" => $employment->employmentType ?? null
            ],
            "directReport" => $user->directReport()->with([
                "manager" => function($q) {
                    $q->select("id", "name", "email")->with(["roles"]);

                },
                "dotlineManager" => function($q) {
                    $q->select("id", "name", "email")->with(["roles"]);
                }
            ])->first(),


        ], 200);
    }

    // User Verify
    public function userVerify($token)
    {
        $verifyUser = UserVerify::with([
            'user'
        ])->where('token', $token)->first();


        if(!$verifyUser) {
            return $this->errorResponse("Sorry your token could not be identified", 404);
        }

        $user = $verifyUser->user;

        if($user->systemStatus->slug == 'pending') {
            try {
                $verifyUser->user->status_id = SystemStatus::where('slug', 'activated')->first()->id;
                $verifyUser->user->email_verified_at = Carbon::now();
                $verifyUser->user->save();
                $verifyUser->delete();
                return $this->successResponse("Your e-mail verified successfully. You can login now", $verifyUser, 200);

            } catch (\Exception $e) {
                return $this->errorResponse($e->getMessage(), $e->getCode());
            }
        }
        else {
            return $this->errorResponse("Your email has already verified. You can login now", 400);
        }
    }

    // Forgot Password
    public function userForgotPassword(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|email|exists:users'
            ],
            [
                'email.required' => 'The :attribute field can not be blank value.'
            ]
        );

        if ($validator->fails())
        {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        $token = Str::random(64);

        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        Mail::to($request->email)->send(new ResetPassword($token));
        return $this->successResponse("Your link Password reset has sended");

    }

    // Reset Password
    public function userResetPassword(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'token' => 'required',
                'password' => 'required|string|min:6|confirmed',
                'password_confirmation' => 'required',
            ],
            [
                'token.required' => 'The :attribute field can not be blank value.'
            ]
        );

        if ($validator->fails())
        {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        $updatePassword = DB::table('password_resets')->where('token',$request->token)->first();

        if($updatePassword){
            $user = User::where('email', $updatePassword->email)->update(['password' => Hash::make($request->password)]);
            DB::table('password_resets')->where('token', $request->token)->delete();

            return $this->successResponse("Your password has been changed!");
        } else {
            return $this->errorResponse("Invalid token!", 400);
        }

    }

    // User Logout
    public function userLogout()
    {

        $user = User::find(request()->user()->id);
        $user->is_active = false;
        $user->save();

        auth()->user()->tokens()->delete();
        return $this->successResponse("Logout successfully. Your token was deleted");
    }
}

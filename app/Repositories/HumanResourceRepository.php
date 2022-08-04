<?php

namespace App\Repositories;

use App\Interfaces\HumanResourceInterface;
use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use Exception;
use App\Models\User;
use App\Models\ManageOrganizationTeam;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class HumanResourceRepository implements HumanResourceInterface
{
    use ResponseAPI;

    private function queryTeams()
    {
        return ManageOrganizationTeam::with([
            "humanResource",
            "grade" => function($q) {
                $q->select("id", "grade_name", "grade_slug");
            },
        ]);
    }

    public function getAllHumanResourceTeams()
    {
        $limit  = 5;

        if(request()->get('limit') && !empty(request()->get('limit')))
        {
            $limit = request()->get('limit');
        }

        try {
            $data = $this->queryTeams()->orderBy("id", "ASC");

            if(request()->get('is_datatable') && Str::lower(request()->get('is_datatable')) == "yes") {
                $data = $data->paginate($limit);
                $data->getCollection = $data->getCollection()->makeHidden([
                    "teams",
                    "created_at",
                    "updated_at"
                ]);
            }
            else {
                $data = $data->get()->makeHidden([
                    "created_at",
                    "updated_at",
                    "teams"
                ]);
            }

            return $this->successResponse("Get all human resource management organization teams", $data);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function saveHumanResource(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'role_name' => 'required',
                'user_id' => 'required|unique:manage_organization_teams,user_id',
                'grade_id' => 'required',
                'teams' => 'required'

            ],
            [
                'user_id.required' => 'The :attribute field can not be blank value.',
                'grade_id.required' => 'The :attribute field can not be blank value.',
                'teams.required' => 'The :attribute field can not be blank value.'
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }


        try {
            
            $role = Role::where('name', $request->role_name)->first();

            if(!$role) {
                return $this->errorResponse("Role Name not found", 404);
            }

            $manageTeam = $this->queryTeams()->create([
                "user_id" => $request->user_id,
                "grade_id" => $request->grade_id,
                "teams" => json_encode($request->teams)
            ]);

            $permissions = Permission::get();
            $user = User::with('roles')->where('id', $request->user_id)->first();
            $user->syncRoles([$role->id]);
            $user->syncPermissions($permissions);

            return $this->successResponse("Human resource add successfully", $manageTeam->setHidden([
                "created_at",
                "updated_at",
                "teams"
            ]), 201);
        } catch (\Exception $e) {
            return 'asdas';
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function updateHumanResource(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'role_name' => 'required',
                'user_id' => 'required|unique:manage_organization_teams,user_id,'.$id,
                'grade_id' => 'required',
                'teams' => 'required'

            ],
            [
                'user_id.required' => 'The :attribute field can not be blank value.',
                'grade_id.required' => 'The :attribute field can not be blank value.',
                'teams.required' => 'The :attribute field can not be blank value.'
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }


        $data = $this->queryTeams()->find($id);

        if(!$data) {
            return $this->errorResponse("Manage organization ID of Human resource not found", 404);
        }


        if($request->user_id && $request->user_id != $data->user_id) {
            $checkUserId = $this->queryTeams()->where('user_id', $request->user_id)->first();

            if($checkUserId) {
                return $this->errorResponse("Opss, Sorry the user has already added as other Human Resources", 400);
            }
        }

        try {

            $role = Role::where('name', $request->role_name)->first();

            if(!$role) {
                return $this->errorResponse("Role Name not found", 404);
            }


            $data->user_id = $request->user_id ?? $data->user;
            $data->grade_id = $request->grade_id ?? $data->grade_id;


            if($data->teams != null && !empty($data->teams))
            {
                $teams = json_decode($data->teams, true);

                foreach ($request->teams as  $value) {
                    $checkTeam = collect($teams)->filter(function($x) use($value) {
                        return $x == $value;
                    });

                    if(count($checkTeam) <= 0) {
                        array_push($teams, $value);
                    }
                }

                $data->teams = json_encode($teams);
            }
            else
            {
                $data->teams = json_encode($request->teams);
            }


            $data->save();

            $permissions = Permission::get();
            $user = User::with('roles')->where('id', $request->user_id)->first();
            $user->syncRoles([$role->id]);
            $user->syncPermissions($permissions);

            return $this->successResponse("Human resource update successfully",
                $data->setHidden(["teams", "created_at", "updated_at"])
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
}

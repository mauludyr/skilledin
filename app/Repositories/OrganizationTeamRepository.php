<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

use App\Interfaces\OrganizationTeamInterface;
use App\Models\OrganizationTeam;
use App\Traits\ResponseAPI;
use Exception;

class OrganizationTeamRepository implements OrganizationTeamInterface
{
    use ResponseAPI;

    private function queryOrganizationTeam()
    {
        return OrganizationTeam::with([
            "managerTeam" => function($q) {
                $q->select("id", "name", "email");
            },
            "members" => function($q) {
                $q->with([
                    "user" => function($Q) {
                        $Q->with([
                            "employment" => function ($c) {
                                $c->with([
                                    'grade' => function($x) {
                                        $x->select('id','grade_name', 'grade_slug');
                                    }
                                ])->select("id", "user_id", "grade_id");
                            }

                        ])->select("id", "name", "email");
                    }
                ]);
            },
            "businessPartners" => function($q) {
                $q->with([
                    "user" => function($Q) {
                        $Q->with([
                            "employment" => function ($c) {
                                $c->with([
                                    'grade' => function($x) {
                                        $x->select('id','grade_name', 'grade_slug');
                                    }
                                ])->select("id", "user_id", "grade_id");
                            }

                        ])->select("id", "name", "email");
                    }
                ])->select("id", "organization_team_id", "partner_id");
            },
            "parentTeam",
            "listTeams"
        ]);
    }

    // Get All Organization Team
    public function getAllOrganizationTeams()
    {
        try {
            $data = $this->queryOrganizationTeam()->get();
            return $this->successResponse("Get all organization team", $data);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Find Organization Team By ID
    public function findOrganizationTeam($id)
    {
        try {
            $data = $this->queryOrganizationTeam()->find($id);
            return $this->successResponse("Find organization team", $data);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Save Organization Team
    public function saveOrganizationTeam(Request $request)
    {
        $request->team_name = ucwords(Str::lower($request->team_name));


        $validator = Validator::make(
            $request->all(),
            [
                'team_name' => 'required|unique:organization_teams,team_name',
                'manager_team_id' => 'required',
                'team_members' => 'required|array',
                'team_business_partners' => 'required|array'
            ],
            [
                'team_name.required' => 'The :attribute field can not be blank value.',
                'manager_team_id.required' => 'The :attribute field can not be blank value.',
                'team_members.required' => 'The :attribute field can not be blank value.',
                'team_business_partners.required' => 'The :attribute field can not be blank value.',
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }


        if(count($request->team_members) <= 0) {
            return $this->errorResponse("List of team members must be filled", 400);
        }

        if(count($request->team_business_partners) <= 0) {
            return $this->errorResponse("List of team business partners must be filled", 400);
        }

        try {

            $organizationTeam = OrganizationTeam::create([
                "team_name" => $request->team_name,
                "manager_team_id" => $request->manager_team_id,
                "parent_team_id" => $request->parent_team_id ?? null,
            ]);


            foreach ($request->team_members as $member) {
                $organizationTeam->members()->create([
                    "member_id" => $member,
                ]);
            }

            foreach ($request->team_business_partners as $partner) {
                $organizationTeam->businessPartners()->create([
                    "partner_id" => $partner,
                ]);
            }

            return $this->successResponse("Create new team successfully", $organizationTeam, 201);

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Update Organization Team By ID
    public function updateOrganizationTeam(Request $request, $id)
    {
        $request->team_name = ucwords(Str::lower($request->team_name));

        $validator = Validator::make(
            $request->all(),
            [
                'team_name' => 'unique:organization_teams,team_name,'. $id,
                'team_members' => 'array',
                'team_business_partners' => 'array'
            ],
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        $organizationTeam = OrganizationTeam::find($id);

        if(!$organizationTeam) {
            return $this->errorResponse("Organization team not found", 404);
        }

        try {
            $organizationTeam->team_name = $request->team_name ?? $organizationTeam->team_name;
            $organizationTeam->manager_team_id = $request->manager_team_id ?? $organizationTeam->manager_team_id;
            $organizationTeam->parent_team_id = $request->parent_team_id ?? $organizationTeam->parent_team_id;
            $organizationTeam->save();

            if($request->has("team_members") && count($request->team_members) > 0) {
                $organizationTeam->members()->delete();
                foreach ($request->team_members as $value) {
                    $organizationTeam->members()->create([
                        "member_id" => $value,
                    ]);
                }
            }


            if($request->has("team_business_partners") && count($request->team_business_partners) > 0) {
                $organizationTeam->businessPartners()->delete();
                foreach ($request->team_business_partners as $value) {
                    $organizationTeam->businessPartners()->create([
                        "partner_id" => $value,
                    ]);
                }
            }

            return $this->successResponse("Update organization team success", $organizationTeam);

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }


    }

    // Delete Organization Team By ID
    public function deleteOrganizationTeam($id)
    {
        $organizationTeam = OrganizationTeam::find($id);

        if(!$organizationTeam) {
            return $this->errorResponse("Organization team not found", 404);
        }

        try {

            $name = $organizationTeam->team_name;

            $organizationTeam->members()->delete();
            $organizationTeam->businessPartners()->delete();
            $organizationTeam->delete();
            
            return $this->successResponse("Organization team {$name} has deleted successfully", null);

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }


}

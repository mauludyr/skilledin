<?php

namespace App\Repositories;

use App\Interfaces\ObjectiveInterface;
use App\Models\Objective;
use App\Models\KeyResultObjective;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Traits\ResponseAPI;
use Exception;
use Illuminate\Support\Carbon;

class ObjectiveRepository implements ObjectiveInterface
{
    use ResponseAPI;

    private function queryObjective()
    {
        return Objective::with([
            "owner" => function($q) {
                $q->with([
                    "profile" => function($Q) {
                        $Q->select("id", "user_id", "image_filepath");
                    }
                ])->select("id", "name");
            },
            "durationTime" => function($q) {
                $q->with([
                    "periodTime" => function($x){
                        $x->select("id", "period_name");
                    }
                ]);
            },
            "objectiveLevel" => function($q) {
                $q->select("id", "level_name","level_value");
            },
            "objectiveParentItems",
            "objectiveChildItems",
            "keyResults"  => function($x) {
                $x->with([

                    "measured" => function($q) {
                        $q->select("id", "measure_name", "measure_slug");
                    },
                    "objective" => function($q) {
                        $q->select("id", "name", "description", "owner_id");
                    },
                    "executor" => function($q) {
                        $q->select("id", "name", "email");
                    },
                    "status" => function($q) {
                        $q->select("id", "status_name", "status_slug");
                    },
                    "historyTrackResult" => function($q) {
                        $q->with([
                            "taskStatus" => function($Q) {
                                $Q->select("id", "status_name", "status_slug");
                            },
                        ])->select("id", "progress_value_before", "progress_value_after", "task_status_id", "comment");
                    },
                ]);
            },
        ])->withCount("keyResults");
    }

    // Get All Objective
    public function getAllObjective()
    {
        try {
            $results = $this->queryObjective()->get();
            return $this->successResponse("Get all objective", $results);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Find Objective By ID
    public function findObjectiveById($id)
    {
        try {
            $data = $this->queryObjective()->find($id);

            if(!$data) {
                return $this->errorResponse("Objective not found", 404);
            }


            return $this->successResponse("Success to find objective", $data);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Create new Objective
    public function saveObjective(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|unique:objectives,name',
                'duration_period_id' => 'required',
                'due_date' => 'required',
                'owner_id'=> 'required',
                'objective_level_id' => 'required',
                'is_save_draf' => 'required',
                'key_results.*.title' => 'required|unique:key_result_objectives,title', 
                'key_results.*.measure_id' => 'required',
                'key_results.*.start_value' => 'required',
                'key_results.*.target' => 'required',
                'key_results.*.due_date' => 'required',
                'key_results.*.owner_id' => 'required'
            ],
            [
                'name.required' => 'The :attribute field can not be blank value.',
                'duration_period_id.required' => 'The :attribute field can not be blank value.',
                'due_date.required' => 'The :attribute field can not be blank value.',
                'owner_id.required'=> 'The :attribute field can not be blank value.',
                'objective_level_id.required' => 'The :attribute field can not be blank value.',
                'is_save_draf.required' => 'The :attribute field can not be blank value.',
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        try {
            $data = Objective::create([
                "name" => $request->name,
                "description" => $request->description ?? null,
                "duration_period_id" => $request->duration_period_id,
                "due_date" => $request->due_date,
                "owner_id" => $request->owner_id,
                "objective_level_id" => $request->objective_level_id,
                "parent_object_id" => $request->parent_object_id ?? null,
                "is_save_draf" => $request->is_save_draf == 0 ? false : true,
                "is_new" => true,
                "objective_status" => $request->is_save_draf == true ? "DRAFT" : "ON TRACK",
            ]);

            foreach ($request->key_results as $key => $value) {
                $dataKR = KeyResultObjective::create([
                    "title" => $value['title'],
                    "objective_id" => $data['id'],
                    "measure_id" => $value['measure_id'],
                    "start_value" => $value['start_value'],
                    "target" => $value['target'],
                    "unit" => $value['unit'],
                    "due_date" => $value['due_date'],
                    "is_draft" => $value['is_draft'] ?? false,
                    "owner_id" => $value['owner_id']
                ]);
            }
            $results = [
                'objective' => $data,
                'key_results' => KeyResultObjective::where('objective_id',$data->id)->get()
            ];
            return $this->successResponse("Objective Key Result created successfully", $results, 201);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Update Objective by Id
    public function updateObjective(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|unique:objectives,name,'.$id,
            ],
            [
                'name.required' => 'The :attribute field can not be blank value.',
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        $data = Objective::find($id);

        if(!$data) {
            return $this->errorResponse("Objective not found", 404);
        }

        try {
            $data->name =  $request->name ?? $data->name;
            $data->description = $request->description;
            $data->duration_period_id = $request->duration_period_id ?? $data->duration_period_id;

            $data->due_date = $request->due_date ?? $data->due_date;
            $data->owner_id = $request->owner_id ?? $data->owner_id;
            $data->objective_level_id = $request->objective_level_id ?? $data->objective_level_id;
            $data->parent_object_id = $request->parent_object_id;
            $data->is_save_draf = $request->is_save_draf ?? $data->is_save_draf;
            $data->save();

            return $this->successResponse("Objective updated successfully", $data, 201);

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Delete Objective
    public function deleteObjective($id)
    {
        try {
            $data = Objective::with(['keyResults'])->find($id);

            if (!$data) {
                return $this->errorResponse("Objective not found", 404);
            }

            $name = $data->name;

            if(count($data->keyResults) > 0) {
                $data->keyResults()->delete();
            }

            $data->delete();

            return $this->successResponse("Objective {$name} deleted successfully");
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Get All Parent Objective
    public function getAllParentObjective()
    {
        try {
            $results = $this->queryObjective()->where('parent_object_id', null)->get();
            return $this->successResponse("Get all objective", $results);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Set Parent Objective By Id
    public function setParentObjectiveByObjectiveId(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'parent_objective_id' => 'required',
            ],
            [
                'parent_objective_id.required' => 'The :attribute field can not be blank value.',
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        $data = Objective::find($id);

        if(!$data) {
            return $this->errorResponse("Objective not found", 404);
        }

        try {
            $data->parent_object_id = $request->parent_objective_id;
            $data->save();

            return $this->successResponse("Objective {$data->name} assigned to parent successfully", $data, 200);

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }

    }

    public function getCountObjective()
    {
        $results = Objective::with(["keyResults"])
            ->withCount("keyResults")
            ->get();
            // ->filter(function($q) {
            //     return $q->key_results_count > 0;
            // });
        $groupMap = [
            "on_track" => $results->where("objective_status", "ON TRACK")->count(),
            "off_track" => $results->where("objective_status", "OFF TRACK")->count(),
            "at_risk" => $results->where("objective_status", "AT RISK")->count(),
            "save_draf" => $results->where("is_save_draf", true)->count(),
        ];


        return $this->successResponse("Get all group objective", $groupMap);
    }


}

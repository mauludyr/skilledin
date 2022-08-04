<?php

namespace App\Repositories;

use App\Interfaces\OKRInterface;
use App\Models\HistoryKeyResultUser;
use App\Models\KeyResultObjective;
use App\Models\KeyResultUser;
use App\Models\Objective;
use App\Models\ObjectiveLevel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

use App\Traits\ResponseAPI;
use Exception;
use Illuminate\Support\Facades\Auth;

class OKRRepository implements OKRInterface
{
    use ResponseAPI;

    private function queryKeyResultObjective()
    {
        return KeyResultObjective::with([
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
                ])->select("id", "progress_value", "task_status_id", "comment");
            },
        ]);
    }

    private function calcTotalProgress($object, $value)
    {
        $total = 0;

        $differences = abs($object->start_value - $object->target);

        if($object->measured->measure_slug == 'truefalse')
        {
            $total = $value == $object->target ? 100 : 0;
        }
        else
        {
            $separate = abs($value - $object->start_value);
            $total = ($separate * 100) / $differences;
            $total = ceil($total) == 100 ? ceil($total) : round($total, 1);
        }

        return $total;
    }

    private function validateCheckInValue($object, $value)
    {
        if($object->measured->measure_slug == "truefalse") {
            if($value < 0 || $value > 1) {
                return [
                    "status" => false,
                    "error" => "Progress value must be 0 or 1"
                ];
            }
        }
        else {
            if($value > $object->target) {
                if($value > $object->start_value) {
                    return [
                        "status" => false,
                        "error" => "Progress value must be between {$object->target} to {$object->start_value}"
                    ];
                }
            }
            else {
                if($value < $object->start_value) {
                    return [
                        "status" => false,
                        "error" => "Progress value must be between {$object->start_value} to {$object->target}"
                    ];
                }
            }
        }



        if($object->measured->measure_slug == 'percent')
        {
            if($value < $object->start_value || $value > $object->target)
            {
                return [
                    "status" => false,
                    "error" => "Progress value must be between {$object->start_value} to {$object->target} percent"
                ];
            }
        }
        elseif ($object->measured->measure_slug == 'number')
        {
            if($value < $object->start_value || $value > $object->target)
            {
                return [
                    "status" => false,
                    "error" => "Progress value must be between {$object->start_value} to {$object->target} number"
                ];
            }
        }
        else {

        }

        return [
            "status" => true,
        ];
    }

    private function setStatusObjective($keyResult)
    {
        if($keyResult->objective->is_new == true)
        {
            $keyResult->objective()->update(["is_new" => false]);
        }
    }


    // Create new Objective with Key Result
    public function saveObjectiveKeyResult(Request $request){

    }


    // Get All Key Result By Objective ID
    public function getAllKeyResultByObjectiveId($id)
    {
        try {
            $data = $this->queryKeyResultObjective()->where("objective_id", $id)->get();
            return $this->successResponse("Success to find key results by objective", $data);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Check in Key Result Objective
    public function checkInOKRByAuthUser(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'value' => 'required',
                'status_id' => 'required',
                'comment' => 'required',
                'key_result_id' => 'required',
            ],
            [
                'value.required' => 'The :attribute field can not be blank value.',
                'status_id.required' => 'The :attribute field can not be blank value.',
                // 'comment.required' => 'The :attribute field can not be blank value.',
                'key_result_id.required' => 'The :attribute field can not be blank value.',

            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        $user = $request->user();
        $keyResult = KeyResultObjective::find($request->key_result_id);

        if(!$keyResult) {
            return $this->errorResponse("Key result not found", 404);
        }

        $this->setStatusObjective($keyResult);

        if($keyResult->owner_id != $user->id)
        {
            return $this->errorResponse("You do not have access permission to this Key Result", 401);
        }


        $checkValue = $this->validateCheckInValue($keyResult, $request->value);

        // if($checkValue["status"] == false) {
        //     return $this->errorResponse($checkValue["error"], 400);
        // }

        $totalProgress = $this->calcTotalProgress($keyResult, $request->value);

        $oldProgressValue = $keyResult->last_progress_value != null ? $keyResult->last_progress_value : 0;

        try {

            $keyResult->old_progress_value = $oldProgressValue;
            $keyResult->last_progress_value = $request->value;
            $keyResult->last_status_id = $request->status_id;
            $keyResult->last_comment = $request->comment ?? null;
            $keyResult->total_progress = $totalProgress;
            $keyResult->save();

            if($keyResult) {
                HistoryKeyResultUser::create([
                    "key_result_id" => $keyResult->id,
                    "objective_id" => $keyResult->objective_id,
                    "progress_value_before" => $oldProgressValue,
                    "progress_value_after" => $request->value,
                    "task_status_id" => $request->status_id,
                    "comment" =>  $request->comment ?? null,
                    "user_id" => $user->id,
                ]);
            }

            return $this->successResponse("Check in successfully", [
                "id" => $keyResult->id,
                "key_result_id" => $keyResult->key_result_id,
                "last_progress_value" => $keyResult->last_progress_value,
                "last_status" => $keyResult->status()->select("id","status_name", "status_slug")->first(),
                "last_comment" => $keyResult->last_comment,
                "total_progress" => $totalProgress,
                "owner_id" => $keyResult->owner_id,
                "histories_track" => $keyResult->historyTrackResult()->get(),
            ]);

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Get All Key Result Tracking Of User
    public function getAllKeyResultTrackingUser()
    {
        $user = request()->user();

        try {
            $data = HistoryKeyResultUser::with([
                    "keyResult",
                    "taskStatus" => function($q) {
                        $q->select("id", "status_name", "status_slug");
                    }
                ])
                ->where("user_id", $user->id)
                ->get()
                ->makeHidden(['created_at', 'updated_at', 'deleted_at']);

            if(count($data) <= 0) {
                return $this->errorResponse("Key result tracking user not found", 404);
            }

            return $this->successResponse("Success to find all key result tracking", $data);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }


    // Get All Key Result Tracking With Objective ID
    public function getAllHistoryKeyResultByObjective()
    {
        if(!request()->has("objective_id"))
        {
            return $this->errorResponse("Please insert objective id parameter", 422);
        }


        $objectiveID = request()->get('objective_id');

        try {
            $data = HistoryKeyResultUser::with([
                    "keyResult",
                    "user" => function($x){
                        $x->select("id","name");
                    },
                    "taskStatus" => function($q) {
                        $q->select("id", "status_name", "status_slug");
                    },
                ])
                ->where("objective_id", $objectiveID)
                ->get()
                ->makeHidden(['updated_at', 'deleted_at']);

            if(count($data) <= 0) {
                return $this->errorResponse("Key result tracking user not found", 404);
            }

            return $this->successResponse("Success to find all key result tracking", $data);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }

    }

    // Get OKR Progression
    public function getObjectiveKeyResultProgression()
    {
        $objectives = Objective::withCount('keyResults')
            ->having('key_results_count', '>', 0)
            ->get();
        return $this->successResponse("Success", $objectives);
    }

    // Update OKR Level
    public function updateOkrLevel(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'okr_levels' => 'required',
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }


        try {
            foreach ($request->okr_levels as $value) {
                $data = ObjectiveLevel::find($value['id']);
                $name = ucwords(trim($value["name"]));
                $slug = Str::slug(Str::lower($name));
                if($data) {
                    $data->is_active = $value["is_public"];
                    $data->level_name = $name;
                    $data->level_slug = $slug;
                    $data->save();
                }
                else {
                    return $this->errorResponse("OKR Level failed update", 400);
                }
            }

            return $this->successResponse("OKR Level updated successfully", ObjectiveLevel::get(), 200);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

}

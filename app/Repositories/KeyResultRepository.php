<?php

namespace App\Repositories;

use App\Interfaces\KeyResultInterface;
use App\Models\KeyResultObjective;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Traits\ResponseAPI;
use Exception;

class KeyResultRepository implements KeyResultInterface
{
    use ResponseAPI;

    private function queryKeyResultObjective()
    {
        return KeyResultObjective::with([
            "measured" => function($q) {
                $q->select("id", "measure_name", "measure_slug");
            },
            "objective",
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
    }


    // Find Key Result By ID
    public function findKeyResultById($id)
    {
        try {
            $data = $this->queryKeyResultObjective()->find($id);

            if(!$data) {
                return $this->errorResponse("Key result not found", 404);
            }

            return $this->successResponse("Success to find key result", $data);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Create new Key Result
    public function saveKeyResult(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'title' => 'required', //'required|unique:key_result_objectives,title',
                'objective_id' => 'required',
                'measure_id' => 'required',
                'start_value' => 'required',
                'target' => 'required',
                'due_date' => 'required',
                'owner_id' => 'required'
            ],
            [
                'title.required' => 'The :attribute field can not be blank value.',
                'objective_id.required' => 'The :attribute field can not be blank value.',
                'measure_id.required' => 'The :attribute field can not be blank value.',
                'start_value.required' => 'The :attribute field can not be blank value.',
                'target.required' => 'The :attribute field can not be blank value.',
                'due_date.required'=> 'The :attribute field can not be blank value.',
                'owner_id.required'=> 'The :attribute field can not be blank value.',
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        try {
            $data = KeyResultObjective::create([
                "title" => $request->title,
                "objective_id" => $request->objective_id,
                "measure_id" => $request->measure_id,
                "start_value" => $request->start_value,
                "target" => $request->target,
                "unit" => $request->unit,
                "due_date" => $request->due_date,
                "is_draft" => $request->is_draft ?? false,
                "owner_id" => $request->owner_id
            ]);

            return $this->successResponse("Key result created successfully", $data, 201);

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Update Key Result by Id
    public function updateKeyResult(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'title' => 'required',//'required|unique:key_result_objectives,title,' . $id
            ],
            [
                'title.required' => 'The :attribute field can not be blank value.',
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        $data = KeyResultObjective::find($id);

        if(!$data) {
            return $this->errorResponse("Key result not found", 404);
        }

        try {

            $data->title = $request->title ?? $data->title;
            $data->objective_id = $request->objective_id ?? $data->objective_id ;
            $data->measure_id = $request->measure_id ?? $data->measure_id;
            $data->start_value = $request->start_value ?? $data->start_value;
            $data->target = $request->target ?? $data->target;
            $data->unit = $request->unit ?? $data->unit;
            $data->due_date = $request->due_date ?? $data->due_date;
            $data->is_draft = $request->is_draft ?? $data->is_draft;
            $data->owner_id = $request->owner_id ?? $data->owner_id;

            $data->last_progress_value = $request->last_progress_value ?? $data->last_progress_value;
            $data->last_status_id = $request->last_status_id ?? $data->last_status_id;
            $data->last_comment = $request->last_comment ?? $data->last_comment;

            $data->save();
            return $this->successResponse("Key result updated successfully", $data, 200);

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }


    // Show All Key Result By Auth User
    public function getAllKeyResultByAuthUser()
    {
        $user = request()->user();

        try {
            $data = $this->queryKeyResultObjective()
                ->where("owner_id", $user->id)
                ->get()->makeHidden(['created_at', 'updated_at', 'deleted_at']);

            if(count($data) <= 0) {
                return $this->errorResponse("Key result not found", 404);
            }

            return $this->successResponse("Success to find all key result", $data);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }



}

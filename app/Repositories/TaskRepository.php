<?php

namespace App\Repositories;

use App\Interfaces\TaskInterface;
use App\Models\User;
use App\Models\Task;
use App\Models\TaskLabel;
use App\Models\StatusTask;
use App\Models\KeyResultObjective;
use App\Traits\ResponseAPI;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TaskRepository implements TaskInterface
{
    use ResponseAPI;

    public function queryTask()
    {
        return Task::with([
            'keyResult',
            'status','label',
            'delegate' => function($q) {
                $q->select("id", "name");
            },
        ]);
    }


    // Get All Task
    public function getAllTask()
    {
        try {
            $results = $this->queryTask()
                ->get()
                ->makeHidden(["created_at", "updated_at", "deleted_at"]);

            return $this->successResponse("Get all task", $results);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Get All Task
    public function getStarred()
    {
        $limit  = 3;

        if(request()->get('limit') && !empty(request()->get('limit')))
        {
            $limit = request()->get('limit');
        }

        try {
            $results = $this->queryTask()
                ->where('is_starred', 1)
                ->limit($limit)
                ->get()
                ->makeHidden(["created_at", "updated_at", "deleted_at"]);

            return $this->successResponse("Get all task", $results);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Get All Task Status
    public function getStatusTask()
    {
        try {
            $results = StatusTask::get()->makeHidden(["created_at", "updated_at", "deleted_at"]);

            return $this->successResponse("Get all task", $results);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Get All Task Label
    public function getLabelTask()
    {
        try {
            $results = TaskLabel::get()->makeHidden(["created_at", "updated_at", "deleted_at"]);

            return $this->successResponse("Get all task", $results);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }


    // Find Task By ID
    public function findTaskById($id)
    {
        try {
            $data = $this->queryTask()->find($id);

            if (!$data) {
                return $this->errorResponse("Task not found", 404);
            }

            return $this->successResponse("Success to find Task", $data);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }


    // Save Task
    public function saveTask(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'task_name' => 'required|string',
                'task_note' => 'required|string',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'is_starred' => 'required|boolean',
                'key_result' => 'required',
                'status' => 'required',
                'label' => 'required',
                'delegate' => 'nullable'
            ],
            [
                'task_name.required' => 'The :attribute field can not be blank value.',
                'task_note.required' => 'The :attribute field can not be blank value.',
                'start_date.required' => 'The :attribute field can not be blank value.',
                'end_date.required' => 'The :attribute field can not be blank value.',
                'is_starred.required' => 'The :attribute field can not be blank value.',
                'key_result.required' => 'The :attribute field can not be blank value.',
                'status.required' => 'The :attribute field can not be blank value.',
                'label.required' => 'The :attribute field can not be blank value.',
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        $keyResult = KeyResultObjective::where('owner_id', request()->user()->id)->where('id', $request->key_result)->first();

        if (!$keyResult) {
            return $this->errorResponse("Key results not Found", 400);
        }

        $status = StatusTask::find($request->status);
        if (!$status) {
            return $this->errorResponse("status not Found", 400);
        }

        $label = TaskLabel::find($request->label);
        if (!$label) {
            return $this->errorResponse("label not Found", 400);
        }
        if (!$request->delegate) {
            $delegate = auth()->user()->id;
        } else {
            $delegate = User::with('employee')->whereHas("employee", function($q) use ($request) {
                        $q->where("id", $request->delegate);
                    })->where('id',request()->user()->id)->first();
        }
        
        if (!$delegate) {
            return $this->errorResponse("delegate not Found", 400);
        }

        
        try {
            $data = Task::updateOrCreate([
                'task_name' => $request->task_name,
                'task_note' => $request->task_note,
                'duration' => 'test',
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'is_starred' => $request->is_starred,
                'key_result_id' => $request->key_result,
                'status_id' => $request->status,
                'label_id' => $request->label,
                'delegate_id' => $delegate,
                'created_by' => auth()->user()->id
            ]);

            return $this->successResponse("Task created successfully", $data, 201);

        } catch (\Exception $e) {
            return response()->json($e->getMessage());
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }


    }

    // Save Status Task
    public function saveStatusTask(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|unique:status_tasks,status_name'
            ],
            [
                'name.required' => 'The :attribute field can not be blank value.'
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        $name = ucwords($request->name);
        $slug = Str::slug(Str::lower($name));
        
        try {
            $data = StatusTask::updateOrCreate([
                'status_name' => $name,
                'status_slug' => $slug
            ]);

            return $this->successResponse("Status Task created successfully", $data, 201);

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Save Label Task
    public function saveLabelTask(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|unique:task_labels,label_name'
            ],
            [
                'name.required' => 'The :attribute field can not be blank value.'
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        $name = ucwords($request->name);
        $slug = Str::slug(Str::lower($name));
        
        try {
            $data = TaskLabel::updateOrCreate([
                'label_name' => $name,
                'label_slug' => $slug
            ]);

            return $this->successResponse("Label Task created successfully", $data, 201);

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }


    // Update Task
    public function updateTask(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'field_name' => 'required|unique:profile_field_settings,field_name,'. $id,
            ],
            [
                'field_name.required' => 'The :attribute field can not be blank value.',
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }



        $data = Task::find($id);

        if (!$data) {
            return $this->errorResponse("Task not found", 404);
        }

        $name = $request->field_name ? ucwords($request->field_name) : $data->field_name;
        $slug = Str::slug(Str::lower($name));

        try {

            $data->field_name = $name;
            $data->field_slug = $slug;
            $data->save();

            return $this->successResponse("Task updated successfully", $data);

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }


    }

    // Update Task by Action
    public function updateTaskAction(Request $request, $type, $id)
    {
        
        $data = Task::find($id);

        if (!$data) {
            return $this->errorResponse("Task not found", 404);
        }

        try {
            return $this->successResponse("Task updated successfully", $data);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }


    }


    // Delete Task
    public function deleteTask($id)
    {
        try {
            $data = Task::with("profileSetting")->find($id);
            if (!$data) {
                return $this->errorResponse("Task not found", 404);
            }
            $data->delete();
            return $this->successResponse("Task deleted successfully");
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
}

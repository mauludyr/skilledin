<?php

namespace App\Repositories;

use App\Interfaces\ConversationInterface;
use App\Models\User;
use App\Models\Task;
use App\Models\Objective;
use App\Models\KeyResultObjective;
use App\Models\Conversation;
use App\Models\ConversationOkr;
use App\Models\ConversationTask;
use App\Models\ConversationStep;
use App\Models\ConversationUser;
use App\Traits\ResponseAPI;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ConversationRepository implements ConversationInterface
{
    use ResponseAPI;

    public function listStep()
    {
        $data = collect([
            "Initiate",
            "Prepare",
            "Participate"
        ]);
        try {   
            return $this->successResponse("List Step", $data);
        } catch (\Exception $e) {
             return response()->json($e->getMessage());
        }  
    }

    public function queryConversation()
    {
        return Conversation::with([
            'ConversationWith' => function($q) {
                $q->with([
                    "user" => function($x) {
                        $x->with([
                            "employment" => function($z){
                                $z->with([
                                    "jobPosition" => function($a){
                                        $a->select("id","job_name");
                                    },
                                    "employmentType" => function($b){
                                        $b->select("id","emp_type_name");
                                    }
                                ])->select("id","user_id","job_position_id","employment_type_id");
                            },
                            "roles" => function ($c){
                                $c->select("id","name");
                            },
                            "profile" => function ($c){
                                $c->select("id","user_id","first_name","last_name","middle_name","image_filename","image_filepath");
                            }
                        ])->select("id","name");
                    }
                ])->select("id","conversation_id","user_id","comment");
            },
            'task' => function($q) {
                $q->with([
                    "task" => function($w){
                        $w->select("id","task_name","task_note","duration","start_date","end_date","is_starred","is_completed");
                    }
                ])->select("id","conversation_id","task_id","comment");
            },
            'okr' => function($q) {
                $q->with([
                    "objective" => function($w){
                        $w->with([
                            "status"  => function($e) {
                                $e->select("id","status_name");
                            }
                        ])->select("id","title","start_value","target","unit","due_date","last_status_id");
                    }
                ])->select("id","conversation_id","objective_id");
            },
            'step' => function($q) {
                $q->select("id", "conversation_id", "step_name", "step_date");
            },
        ]);
    }


    // Get All Conversation On Going
    public function getOnGoing()
    {
        try {
            $results = $this->queryConversation()
                ->where('status', 'on-going')
                ->get()
                ->makeHidden(["created_at", "updated_at", "deleted_at"]);

            return $this->successResponse("Get all On-Going Conversation", $results);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Get All Conversation Past
    public function getPast()
    {
        try {
            $results = $this->queryConversation()
                ->where('status', 'on-going')
                ->get()
                ->makeHidden(["created_at", "updated_at", "deleted_at"]);

            return $this->successResponse("Get all Past Conversation", $results);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Save Conversation
    public function saveConversation(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'type' => 'required|string',
                'accomplishment' => 'nullable|string',
                'obstacle' => 'nullable|string',
                'next_step' => 'required|string',
                'step_date' => 'required|date',
                'due_date' => 'required|date|after:step_date',
                'users' => 'required|array',
                'task_id' => 'nullable|array',
                'objective_id' => 'nullable',
                'is_ready' => 'required|boolean'
            ],
            [
                'next_step.required' => 'The :attribute field can not be blank value.',
                'step_date.required' => 'The :attribute field can not be blank value.',
                'due_date.required' => 'The :attribute field can not be blank value.',
                'users.required' => 'The :attribute field can not be blank value.',
                'is_ready.required' => 'The :attribute field can not be blank value.',
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        DB::beginTransaction();

        try {
            $conversation = Conversation::Create([
                'type' => $request->type,
                'accomplishment' => $request->accomplishment,
                'obstacle' => $request->obstacle,
                'next_step' => $request->next_step,
                'step_date' => $request->step_date,
                'due_date' => $request->due_date,
                'is_ready' => $request->is_ready,
                'status' => 'on-going',
                'created_by' => auth()->user()->id
            ]);
            foreach ($request->users as $key => $value) {

                $findUser = User::find($value);
                if (!$findUser){
                    return $this->errorResponse("User not found", 404);
                } else {
                    $user = ConversationUser::Create([
                        'conversation_id' => $conversation->id,
                        'user_id' => $value,
                    ]);
                }
            }
            $initiate = ConversationStep::Create([
                'conversation_id' => $conversation->id,
                'step_name' => 'Initiate',
                'step_date' => $conversation->created_at,
            ]);

            $prepare = ConversationStep::Create([
                'conversation_id' => $conversation->id,
                'step_name' => 'Prepare',
                'step_date' => $conversation->step_date,
            ]);

            $participate = ConversationStep::Create([
                'conversation_id' => $conversation->id,
                'step_name' => 'Participate',
                'step_date' => $conversation->step_date,
            ]);

            $close = ConversationStep::Create([
                'conversation_id' => $conversation->id,
                'step_name' => 'Closed',
                'step_date' => $request->due_date,
            ]);

            if ($request->task_id){
                foreach ($request->task_id as $key => $value) {

                    $findTask = Task::find($value);

                    if (!$findTask){
                       return $this->errorResponse("Task not found", 404);
                    } else {
                        $task = ConversationTask::Create([
                            'conversation_id' => $conversation->id,
                            'task_id' => $value
                        ]);
                    }

                }
            }

            if ($request->objective_id){
                $findObjective = KeyResultObjective::find($request->objective_id);
                if (!$findObjective){
                   return $this->errorResponse("OKR not found", 404);
                }else{
                    $objective = ConversationOkr::Create([
                        'conversation_id' => $conversation->id,
                        'objective_id' => $request->objective_id
                    ]);
                }
            }
        
            DB::commit();
            return $this->successResponse("Conversation created successfully", $conversation, 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json($e->getMessage());
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }


    }

    // Update Conversation
    public function updateConversation(Request $request, $id)
    {
        
        $validator = Validator::make(
            $request->all(),
            [
                'type' => 'required|string',
                'accomplishment' => 'nullable|string',
                'obstacle' => 'nullable|string',
                'next_step' => 'required|string',
                'step_date' => 'required|date',
                'due_date' => 'required|date|after:step_date',
                'is_ready' => 'required|boolean'
            ],
            [
                'type.required' => 'The :attribute field can not be blank value.',
                'next_step.required' => 'The :attribute field can not be blank value.',
                'step_date.required' => 'The :attribute field can not be blank value.',
                'due_date.required' => 'The :attribute field can not be blank value.',
                'is_ready.required' => 'The :attribute field can not be blank value.',
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        $data = Conversation::find($id);

        if (!$data) {
            return $this->errorResponse("Conversation not found", 404);
        }

        try {

            $data->type = $request->type;
            $data->accomplishment = $request->accomplishment;
            $data->obstacle = $request->obstacle;
            $data->next_step = $request->next_step;
            $data->step_date = $request->step_date;
            $data->due_date = $request->due_date;
            $data->is_ready = $request->is_ready;
            $data->save();

            return $this->successResponse("Conversation updated successfully", $data);

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }


    }

    // Add Conversation
    public function addConversation(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'type' => 'required|in:task,okr',
                'conversation_id' => 'required',
                'task_id' => 'required_if:type,task',
                'objective_id' => 'required_if:type,okr',
            ],
            [
                'type.required' => 'The :attribute field can not be blank value.'
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }
        try {
            if ($request->type == 'task'){
                $task = Task::find($request->task_id);
                
                if (!$task){
                    return $this->errorResponse("Task not found", 404);
                }

                $createTask = ConversationTask::Create([
                    'conversation_id' => $request->conversation_id,
                    'task_id' => $task->id
                ]);
            } else {
                $objective = KeyResultObjective::find($request->objective_id);
                
                if (!$objective){
                    return $this->errorResponse("Objective not found", 404);
                }

                $createObjective = ConversationOkr::Create([
                    'conversation_id' => $request->conversation_id,
                    'objective_id' => $objective->id
                ]);
            }

            return $this->successResponse("Add Conversation successfully");

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Add Comment
    public function saveComment(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'type' => 'required|in:task,okr',
                'task_id' => 'required_if:type,task',
                'objective_id' => 'required_if:type,okr',
                'comment' => 'nullable',
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        try {
            if ($request->type == 'task'){
                $task = Task::find($request->task_id);
                
                if (!$task){
                    return $this->errorResponse("Task not found", 404);
                }

                $conversationTask = ConversationTask::where('task_id', $request->task_id)->first();
                if (!$conversationTask) {
                    $createTask = ConversationTask::Create([
                        'task_id' => $task->id,
                        'comment' => $request->comment
                    ]);
                } else {
                    $conversationTask->comment = $request->comment;
                    $conversationTask->save();
                }

            } else {

                $objective = KeyResultObjective::find($request->objective_id);
                
                if (!$objective){
                    return $this->errorResponse("Objective not found", 404);
                }

                $conversationOkr = ConversationOkr::where('objective_id', $objective->id)->first();

                if (!$conversationOkr){
                    $createObjective = ConversationOkr::Create([
                        'objective_id' => $objective->id,
                        'comment' => $request->comment,
                    ]);
                } else {
                    $conversationOkr->comment = $request->comment;
                    $conversationOkr->save();
                }
            }

            return $this->successResponse("Add Comment successfully");

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Add Step
    public function saveStepConversation(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'conversation_id' => 'required',
                'step_name' => 'required',
                'step_date' => 'required|date',
            ],
            [
                'step_name.required' => 'The :attribute field can not be blank value.'
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        try {
            $conversation = Conversation::find($request->conversation_id);
            
            if (!$conversation){
                return $this->errorResponse("Conversation not found", 404);
            }

            $createStep = ConversationStep::Create([
                'conversation_id' => $request->conversation_id,
                'step_name' => $request->step_name,
                'step_date' => $request->step_date
            ]);

            return $this->successResponse("Add Step successfully", $createStep, 201);

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Add Step
    public function saveOkrConversation(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'conversation_id' => 'required',
                'objective_id' => 'required'
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        try {
            $conversation = Conversation::find($request->conversation_id);
            
            if (!$conversation){
                return $this->errorResponse("Conversation not found", 404);
            }

            $okr = KeyResultObjective::find($request->objective_id);

            if (!$okr){
                return $this->errorResponse("Okr not found", 404);
            }

            $createStep = ConversationOkr::Create([
                'conversation_id' => $request->conversation_id,
                'objective_id' => $request->objective_id,
                'step_date' => $request->step_date
            ]);

            return $this->successResponse("Add Step successfully", $createStep, 201);

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Find Conversation By ID
    public function findConversationById($id)
    {
        try {
            $data = $this->queryConversation()->find($id);

            if (!$data) {
                return $this->errorResponse("Conversation not found", 404);
            }

            return $this->successResponse("Success to find Conversation", $data);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Delete Conversation Task
    public function deleteConversationTask($id)
    {
        try {
            $data = ConversationTask::find($id);
            if (!$data) {
                return $this->errorResponse("Conversation Task not found", 404);
            }
            $data->delete();
            return $this->successResponse("Conversation Task deleted successfully");
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Delete Conversation
    public function deleteConversation($id)
    {
        try {
            $data = Conversation::find($id);
            if (!$data) {
                return $this->errorResponse("Conversation not found", 404);
            }
            $data->delete();
            return $this->successResponse("Conversation deleted successfully");
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
}

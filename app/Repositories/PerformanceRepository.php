<?php

namespace App\Repositories;

use App\Interfaces\PerformanceInterface;
use App\Models\Performance;
use App\Models\ReviewerGroup;
use App\Models\FeedbackQuestion;
use App\Traits\ResponseAPI;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PerformanceRepository implements PerformanceInterface
{
    use ResponseAPI;

    // Get Performance Setting
    public function getAllPerformance()
    {
        try {
            $performance        = Performance::select('description')->first();
            $reviewer           = ReviewerGroup::get()->makeHidden(["created_at", "updated_at", "deleted_at"]);
            $generalFeedback    = FeedbackQuestion::where('type', 'general')->get()->makeHidden(["created_at", "updated_at", "deleted_at"]);
            $hrFeedback         = FeedbackQuestion::where('type', 'hr')->get()->makeHidden(["created_at", "updated_at", "deleted_at"]);
            
            $results = [
                "overview"       => $performance->description ?? null,
                "reviewer"          => $reviewer,
                "generalFeedback"   => $generalFeedback,
                "hrFeedback"        => $hrFeedback
            ];

            return $this->successResponse("Get all profile setting", $results);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Update Performance Setting
    public function updatePerformanceSetting(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            ['description' => 'string|nullable']
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        try {

            $performance = Performance::whereNotNull('description')->first();

            if (is_null($performance)) {
                $data = new Performance();
                $data->description = $request->description;
                $data->save();
                return $this->successResponse("Performance setting created successfully", $data, 201);
            } else {
                $updatePerformance = Performance::first();
                $updatePerformance->description = $request->description;
                $updatePerformance->save();
                return $this->successResponse("Performance setting updated successfully", $updatePerformance, 201);
            }


        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Update Reviewer Setting
    public function updateReviewerSetting(Request $request)
    {
        DB::beginTransaction();
        try {

            foreach ($request->all() as $key => $value) {
                $data = ReviewerGroup::find($value['id']);
                $data->is_active = $value['is_active'];
                $data->hr_visible = $value['hr_visible'];
                $data->manager_visible = $value['manager_visible'];
                $data->employee_visible = $value['employee_visible'];
                $data->save();
            }
            DB::commit();
     
            $reviewer = ReviewerGroup::select('id','name','is_active','hr_visible','manager_visible','employee_visible')->get();

            return $this->successResponse("Reviewer setting updated successfully", $reviewer, 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Find Feedback Question By Id
    public function findFeedbackQuestion($id)
    {
        try {
            $data = FeedbackQuestion::find($id);

            if (!$data) {
                return $this->errorResponse("Feedback not found", 404);
            }

            return $this->successResponse("Success to find Profile setting", $data);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Save Feedback Question
    public function saveFeedbackQuestion(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'description' => 'required|string',
                'type' => 'required|string',
                'active_self' => 'required|boolean',
                'active_direct_manager' => 'required|boolean',
                'active_dotted_line_manager' => 'required|boolean',
                'active_peers' => 'required|boolean',
                'active_reverse_review' => 'required|boolean'
            ],
            [
                'description.required' => 'The :attribute field can not be blank value.'
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        DB::beginTransaction();

        try {
            
            $data = new FeedbackQuestion();
            $data->description = $request->description;
            $data->type = $request->type;
            $data->active_self = $request->active_self;
            $data->active_direct_manager = $request->active_direct_manager;
            $data->active_dotted_line_manager = $request->active_dotted_line_manager;
            $data->active_peers = $request->active_peers;
            $data->active_reverse_review = $request->active_reverse_review;
            $data->save();

            DB::commit();

            return $this->successResponse("Feedback created successfully", $data, 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Update Feedback Question
    public function updateFeedbackQuestion(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'description' => 'required|string',
                'active_self' => 'required|boolean',
                'active_direct_manager' => 'required|boolean',
                'active_dotted_line_manager' => 'required|boolean',
                'active_peers' => 'required|boolean',
                'active_reverse_review' => 'required|boolean'
            ],
            [
                'description.required' => 'The :attribute field can not be blank value.'
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        $data = FeedbackQuestion::find($id);

        if (!$data) {
            return $this->errorResponse("Feedback not found", 404);
        }

        DB::beginTransaction();
        try {

            $data->description = $request->description;
            $data->active_self = $request->active_self;
            $data->active_direct_manager = $request->active_direct_manager;
            $data->active_dotted_line_manager = $request->active_dotted_line_manager;
            $data->active_peers = $request->active_peers;
            $data->active_reverse_review = $request->active_reverse_review;
            $data->save();

            DB::commit();

            return $this->successResponse("Feedback updated successfully", $data);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }

    }

    // Delete Feedback Question
    public function deleteFeedbackQuestion($id)
    {
        DB::beginTransaction();
        try {
            $data = FeedbackQuestion::find($id);

            if (!$data) {
                return $this->errorResponse("Feedback not found", 404);
            }

            $data->delete();

            DB::commit();

            return $this->successResponse("Feedback deleted successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
}

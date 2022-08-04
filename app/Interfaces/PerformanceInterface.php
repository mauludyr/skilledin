<?php
namespace App\Interfaces;

use Illuminate\Http\Request;

interface PerformanceInterface
{
    public function getAllPerformance();
    public function updatePerformanceSetting(Request $request);
    public function updateReviewerSetting(Request $request);
    public function findFeedbackQuestion($id);
    public function saveFeedbackQuestion(Request $request);
    public function updateFeedbackQuestion(Request $request, $id);
    public function deleteFeedbackQuestion($id);
}

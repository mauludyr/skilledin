<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interfaces\PerformanceInterface;
use Illuminate\Http\Request;

class PerformanceController extends Controller
{
    protected $performanceInterface;

    public function __construct(PerformanceInterface $performanceInterface)
    {
        $this->performanceInterface = $performanceInterface;
    }

    public function showAll() {
        return $this->performanceInterface->getAllPerformance();
    }

    public function updatePerformance(Request $request)
    {
        return $this->performanceInterface->updatePerformanceSetting($request);
    }

    public function updateSetting(Request $request)
    {
        return $this->performanceInterface->updateReviewerSetting($request);
    }

    public function showById($id)
    {
        return $this->performanceInterface->findFeedbackQuestion($id);
    }

    public function storeFeedbackQuestion(Request $request)
    {
        return $this->performanceInterface->saveFeedbackQuestion($request);
    }

    public function updateFeedback(Request $request, $id)
    {
        return $this->performanceInterface->updateFeedbackQuestion($request, $id);
    }

    public function destroyFeedbackQuestion($id)
    {
        return $this->performanceInterface->deleteFeedbackQuestion($id);
    }
}

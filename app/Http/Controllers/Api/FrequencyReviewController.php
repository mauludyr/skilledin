<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interfaces\FrequencyPeriodInterface;
use App\Interfaces\FrequencyReviewInterface;
use Illuminate\Http\Request;

class FrequencyReviewController extends Controller
{
    protected $frInterview;
    protected $frequency;

    public function __construct(FrequencyReviewInterface $interfaces, FrequencyPeriodInterface $frequency)
    {
        $this->frInterview = $interfaces;
        $this->frequency = $frequency;
    }

    public function showAllFrequency()
    {
        return $this->frequency->getAllFrequency();
    }

    public function showReviewDate()
    {
        return $this->frInterview->getReviewDate();
    }

    public function upgradeReview(Request $request)
    {
        return $this->frInterview->upgradeReview($request);
    }
}

<?php
namespace App\Interfaces;

use Illuminate\Http\Request;

interface FrequencyReviewInterface
{
    public function upgradeReview(Request $request);
    public function getReviewDate();
}

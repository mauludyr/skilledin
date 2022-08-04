<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interfaces\VisibilityInterface;
use Illuminate\Http\Request;

class VisibilityController extends Controller
{
    protected $visibilityInterface;

    public function __construct(VisibilityInterface $interfaces)
    {
        $this->visibilityInterface = $interfaces;
    }

    // Show All Visibilities
    public function showAllVisibilities()
    {
        return $this->visibilityInterface->showAllVisibilities();
    }
}

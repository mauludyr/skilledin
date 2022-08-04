<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface HumanResourceInterface
{
    public function getAllHumanResourceTeams();
    public function saveHumanResource(Request $request);
    public function updateHumanResource(Request $request, $id);
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interfaces\OrganizationTeamInterface;
use Illuminate\Http\Request;

class OrganizationTeamController extends Controller
{
    protected $teamInterface;

    public function __construct(OrganizationTeamInterface $interfaces)
    {
        $this->teamInterface = $interfaces;
    }

    // Show All Organization Teams
    public function showAllTeams()
    {
        return $this->teamInterface->getAllOrganizationTeams();
    }

    // Show Organization Team By Id
    public function findTeamById($id)
    {
        return $this->teamInterface->findOrganizationTeam($id);
    }

    //Store Organization Team
    public function storeTeam(Request $request)
    {
        return $this->teamInterface->saveOrganizationTeam($request);
    }

    //Update Organization Team
    public function updateTeam(Request $request, $id)
    {
        return $this->teamInterface->updateOrganizationTeam($request, $id);
    }

    //Delete Organization Team
    public function deleteTeam($id)
    {
        return $this->teamInterface->deleteOrganizationTeam($id);
    }
}

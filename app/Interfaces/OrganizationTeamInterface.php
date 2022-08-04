<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface OrganizationTeamInterface
{

    public function getAllOrganizationTeams();

    public function findOrganizationTeam($id);

    public function saveOrganizationTeam(Request $request);

    public function updateOrganizationTeam(Request $request, $id);

    public function deleteOrganizationTeam($id);
}

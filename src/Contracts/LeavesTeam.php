<?php

namespace LiraUi\Team\Contracts;

use App\Models\User;
use LiraUi\Team\Http\Requests\LeaveTeamRequest;
use LiraUi\Team\Models\Team;

interface LeavesTeam
{
    /**
     * Remove the team member from the given team.
     */
    public function leave(LeaveTeamRequest $request, Team $team, ?User $teamMember = null): void;
}

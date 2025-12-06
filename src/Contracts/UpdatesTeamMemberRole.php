<?php

namespace LiraUi\Team\Contracts;

use App\Models\User;
use LiraUi\Team\Http\Requests\UpdateTeamMemberRoleRequest;
use LiraUi\Team\Models\Team;

interface UpdatesTeamMemberRole
{
    /**
     * Update the role of a team member.
     */
    public function update(UpdateTeamMemberRoleRequest $request, Team $team, User $teamMember): void;
}
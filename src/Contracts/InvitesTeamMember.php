<?php

namespace LiraUi\Team\Contracts;

use LiraUi\Team\Http\Requests\InviteTeamMemberRequest;
use LiraUi\Team\Models\Team;

interface InvitesTeamMember
{
    /**
     * Invite a new team member to the given team.
     */
    public function invite(InviteTeamMemberRequest $request, Team $team): void;
}

<?php

namespace LiraUi\Team\Contracts;

use LiraUi\Team\Http\Requests\AcceptTeamInvitationRequest;
use LiraUi\Team\Models\TeamInvitation;

interface AddsTeamMember
{
    /**
     * Add a new team member to the given team.
     */
    public function add(AcceptTeamInvitationRequest $request, TeamInvitation $invitation): void;
}

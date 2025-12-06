<?php

namespace LiraUi\Team\Contracts;

use LiraUi\Team\Http\Requests\UpdateTeamInvitationRequest;
use LiraUi\Team\Models\TeamInvitation;

interface UpdatesTeamInvitation
{
    /**
     * Update the given team invitation.
     */
    public function update(UpdateTeamInvitationRequest $request, TeamInvitation $invitation): void;
}

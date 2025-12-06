<?php

namespace LiraUi\Team\Actions;

use LiraUi\Team\Contracts\UpdatesTeamInvitation;
use LiraUi\Team\Http\Requests\UpdateTeamInvitationRequest;
use LiraUi\Team\Models\TeamInvitation;

class UpdateTeamInvitationAction implements UpdatesTeamInvitation
{
    /**
     * Update the given team invitation.
     */
    public function update(UpdateTeamInvitationRequest $request, TeamInvitation $invitation): void
    {
        $validated = $request->validated();

        $invitation->update([
            'role_id' => $validated['role_id'],
        ]);
    }
}

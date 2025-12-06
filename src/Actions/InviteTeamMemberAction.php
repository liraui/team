<?php

namespace LiraUi\Team\Actions;

use Illuminate\Support\Facades\Mail;
use LiraUi\Team\Contracts\InvitesTeamMember;
use LiraUi\Team\Http\Requests\InviteTeamMemberRequest;
use LiraUi\Team\Mail\TeamInvitation;
use LiraUi\Team\Models\Team;

class InviteTeamMemberAction implements InvitesTeamMember
{
    /**
     * Invite a new team member to the given team.
     */
    public function invite(InviteTeamMemberRequest $request, Team $team): void
    {
        $validated = $request->validated();

        $invitation = $team->teamInvitations()->create([
            'email' => $validated['email'],
            'role_id' => $validated['role_id'],
        ]);

        Mail::to($validated['email'])->send(new TeamInvitation($invitation));
    }
}

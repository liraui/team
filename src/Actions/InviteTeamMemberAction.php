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
        $email = $request->input('email');

        $invitation = $team->teamInvitations()->create([
            'email' => $email,
            'role_id' => $request->input('role_id'),
        ]);

        /** @var \LiraUi\Team\Models\TeamInvitation $invitation */
        Mail::to($email)->send(new TeamInvitation($invitation));
    }
}

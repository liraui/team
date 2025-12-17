<?php

namespace LiraUi\Team\Http\Responses;

use LiraUi\Team\Contracts\TeamMemberInvited;
use LiraUi\Team\Http\Requests\InviteTeamMemberRequest;
use Symfony\Component\HttpFoundation\Response;

class TeamMemberInvitedResponse implements TeamMemberInvited
{
    /**
     * Create an HTTP response for when a team member has been invited.
     */
    public function toResponse(InviteTeamMemberRequest $request): Response
    {
        if ($request->wantsJson()) {
            return response()->json([
                'type' => 'success',
                'message' => 'Team member has been invited successfully.',
            ]);
        }

        return back(303)->with('flash', [
            'type' => 'success',
            'message' => 'Team member has been invited successfully.',
        ]);
    }
}

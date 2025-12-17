<?php

namespace LiraUi\Team\Http\Responses;

use Illuminate\Http\Request;
use LiraUi\Team\Contracts\TeamInvitationAccepted;
use LiraUi\Team\Models\Team;
use Symfony\Component\HttpFoundation\Response;

class TeamInvitationAcceptedResponse implements TeamInvitationAccepted
{
    /**
     * Create an HTTP response for when a team invitation has been accepted.
     */
    public function toResponse(Request $request, Team $team): Response
    {
        if ($request->wantsJson()) {
            return response()->json([
                'type' => 'success',
                'message' => 'Team invitation has been accepted successfully.',
            ]);
        }

        return redirect()->intended(route('teams.settings', $team))->with('flash', [
            'type' => 'success',
            'message' => 'Team invitation has been accepted successfully.',
        ]);
    }
}

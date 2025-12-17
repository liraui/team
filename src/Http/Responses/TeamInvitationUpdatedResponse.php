<?php

namespace LiraUi\Team\Http\Responses;

use LiraUi\Team\Contracts\TeamInvitationUpdated;
use LiraUi\Team\Http\Requests\UpdateTeamInvitationRequest;
use Symfony\Component\HttpFoundation\Response;

class TeamInvitationUpdatedResponse implements TeamInvitationUpdated
{
    /**
     * Create an HTTP response for when a team invitation has been updated.
     */
    public function toResponse(UpdateTeamInvitationRequest $request): Response
    {
        if ($request->wantsJson()) {
            return response()->json([
                'type' => 'success',
                'message' => 'Team invitation has been updated successfully.',
            ]);
        }

        return back(303)->with('flash', [
            'type' => 'success',
            'message' => 'Team invitation has been updated successfully.',
        ]);
    }
}

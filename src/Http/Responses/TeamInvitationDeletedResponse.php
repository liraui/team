<?php

namespace LiraUi\Team\Http\Responses;

use Illuminate\Http\Request;
use LiraUi\Team\Contracts\TeamInvitationDeleted;
use Symfony\Component\HttpFoundation\Response;

class TeamInvitationDeletedResponse implements TeamInvitationDeleted
{
    /**
     * Create an HTTP response for when a team invitation has been deleted.
     */
    public function toResponse(Request $request): Response
    {
        if ($request->wantsJson()) {
            return response()->json([
                'type' => 'success',
                'message' => 'Team invitation has been deleted successfully.',
            ]);
        }

        return back(303)->with('flash', [
            'type' => 'success',
            'message' => 'Team invitation has been deleted successfully.',
        ]);
    }
}

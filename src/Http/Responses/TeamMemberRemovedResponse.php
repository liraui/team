<?php

namespace LiraUi\Team\Http\Responses;

use Illuminate\Http\Request;
use LiraUi\Team\Contracts\TeamMemberRemoved;
use Symfony\Component\HttpFoundation\Response;

class TeamMemberRemovedResponse implements TeamMemberRemoved
{
    /**
     * Create an HTTP response for when a team member has been removed.
     */
    public function toResponse(Request $request): Response
    {
        if ($request->wantsJson()) {
            return response()->json([
                'type' => 'success',
                'message' => 'Team member has been removed successfully.',
            ]);
        }

        return back(303)->with('flash', [
            'type' => 'success',
            'message' => 'Team member has been removed successfully.',
        ]);
    }
}

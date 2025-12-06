<?php

namespace LiraUi\Team\Http\Responses;

use LiraUi\Team\Contracts\TeamMemberRoleUpdated;
use LiraUi\Team\Http\Requests\UpdateTeamMemberRoleRequest;
use Symfony\Component\HttpFoundation\Response;

class TeamMemberRoleUpdatedResponse implements TeamMemberRoleUpdated
{
    /**
     * Create an HTTP response for when a team member's role has been updated.
     */
    public function toResponse(UpdateTeamMemberRoleRequest $request): Response
    {
        if ($request->wantsJson()) {
            return response()->json([
                'type' => 'success',
                'message' => 'Team member role has been updated successfully.',
            ]);
        }

        return back(303)->with('flash', [
            'type' => 'success',
            'message' => 'Team member role has been updated successfully.',
        ]);
    }
}

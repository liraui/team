<?php

namespace LiraUi\Team\Http\Responses;

use LiraUi\Team\Contracts\TeamNameUpdated;
use LiraUi\Team\Http\Requests\UpdateTeamNameRequest;
use Symfony\Component\HttpFoundation\Response;

class TeamNameUpdatedResponse implements TeamNameUpdated
{
    /**
     * Create an HTTP response for when a team name has been updated.
     */
    public function toResponse(UpdateTeamNameRequest $request): Response
    {
        if ($request->wantsJson()) {
            return response()->json([
                'type' => 'success',
                'message' => 'Team name has been updated successfully.',
            ]);
        }

        return back()->with('flash', [
            'type' => 'success',
            'message' => 'Team name has been updated successfully.',
        ]);
    }
}

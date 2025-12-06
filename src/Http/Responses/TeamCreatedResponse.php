<?php

namespace LiraUi\Team\Http\Responses;

use LiraUi\Team\Contracts\TeamCreated;
use LiraUi\Team\Http\Requests\CreateTeamRequest;
use LiraUi\Team\Models\Team;
use Symfony\Component\HttpFoundation\Response;

class TeamCreatedResponse implements TeamCreated
{
    /**
     * Create an HTTP response for when a team has been created.
     */
    public function toResponse(CreateTeamRequest $request, Team $team): Response
    {
        if ($request->wantsJson()) {
            return response()->json([
                'type' => 'success',
                'message' => 'Team has been created successfully.',
            ]);
        }

        return redirect()->route('teams.settings', $team)->with('flash', [
            'type' => 'success',
            'message' => 'Team has been created successfully.',
        ]);
    }
}

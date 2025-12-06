<?php

namespace LiraUi\Team\Http\Responses;

use LiraUi\Team\Contracts\CurrentTeamUpdated;
use LiraUi\Team\Http\Requests\UpdateCurrentTeamRequest;
use Symfony\Component\HttpFoundation\Response;

class CurrentTeamUpdatedResponse implements CurrentTeamUpdated
{
    /**
     * Create an HTTP response for when the current team has been updated.
     */
    public function toResponse(UpdateCurrentTeamRequest $request): Response
    {
        if ($request->wantsJson()) {
            return response()->json([
                'type' => 'success',
                'message' => 'Current team has been updated successfully.',
            ]);
        }

        return redirect()->route('teams.settings', $request->team_id)->with('flash', [
            'type' => 'success',
            'message' => 'Current team has been updated successfully.',
        ]);
    }
}

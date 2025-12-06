<?php

namespace LiraUi\Team\Http\Responses;

use Illuminate\Http\Request;
use LiraUi\Team\Contracts\TeamLeft;
use Symfony\Component\HttpFoundation\Response;

class TeamLeftResponse implements TeamLeft
{
    /**
     * Create an HTTP response for when the user has left a team.
     */
    public function toResponse(Request $request): Response
    {
        if ($request->wantsJson()) {
            return response()->json([
                'type' => 'success',
                'message' => 'You have left the team.',
            ]);
        }

        return redirect()->route('teams.settings', $request->user()->currentTeam)->with('flash', [
            'type' => 'success',
            'message' => 'You have left the team.',
        ]);
    }
}

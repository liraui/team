<?php

namespace LiraUi\Team\Http\Responses;

use LiraUi\Team\Contracts\TeamDeleted;
use LiraUi\Team\Http\Requests\DeleteTeamRequest;
use Symfony\Component\HttpFoundation\Response;

class TeamDeletedResponse implements TeamDeleted
{
    /**
     * Create an HTTP response for when a team has been deleted.
     */
    public function toResponse(DeleteTeamRequest $request): Response
    {
        if ($request->wantsJson()) {
            return response()->json([
                'type' => 'success',
                'message' => 'Team deleted successfully.',
            ]);
        }

        return redirect()->route('dashboard')->with('flash', [
            'type' => 'success',
            'message' => 'Team deleted successfully.',
        ]);
    }
}

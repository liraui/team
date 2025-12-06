<?php

namespace LiraUi\Team\Http\Responses;

use Illuminate\Http\Request;
use LiraUi\Team\Contracts\TeamRoleCreated;
use Symfony\Component\HttpFoundation\Response;

class TeamRoleCreatedResponse implements TeamRoleCreated
{
    /**
     * Create an HTTP response that represents the object.
     */
    public function toResponse(Request $request): Response
    {
        if ($request->wantsJson()) {
            return response()->json([
                'type' => 'success',
                'message' => 'Role created successfully.',
            ]);
        }

        return back()->with('flash', [
            'type' => 'success',
            'message' => 'Role created successfully.',
        ]);
    }
}

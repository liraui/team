<?php

namespace LiraUi\Team\Http\Responses;

use Illuminate\Http\Request;
use LiraUi\Team\Contracts\TeamRoleDeleted;
use Symfony\Component\HttpFoundation\Response;

class TeamRoleDeletedResponse implements TeamRoleDeleted
{
    /**
     * Create an HTTP response that represents the object.
     */
    public function toResponse(Request $request): Response
    {
        if ($request->wantsJson()) {
            return response()->json([
                'type' => 'success',
                'message' => 'Role deleted successfully.',
            ]);
        }

        return back()->with('flash', [
            'type' => 'success',
            'message' => 'Role deleted successfully.',
        ]);
    }
}

<?php

namespace LiraUi\Team\Contracts;

use Illuminate\Http\Request;
use LiraUi\Team\Models\Team;
use Symfony\Component\HttpFoundation\Response;

interface TeamInvitationAccepted
{
    /**
     * Create an HTTP response for when a team invitation has been accepted.
     */
    public function toResponse(Request $request, Team $team): Response;
}
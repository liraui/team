<?php

namespace LiraUi\Team\Contracts;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

interface TeamInvitationDeleted
{
    /**
     * Create an HTTP response for when a team invitation has been deleted.
     */
    public function toResponse(Request $request): Response;
}

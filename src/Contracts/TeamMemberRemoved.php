<?php

namespace LiraUi\Team\Contracts;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

interface TeamMemberRemoved
{
    /**
     * Create an HTTP response for when a team member has been removed.
     */
    public function toResponse(Request $request): Response;
}

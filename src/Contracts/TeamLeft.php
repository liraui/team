<?php

namespace LiraUi\Team\Contracts;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

interface TeamLeft
{
    /**
     * Create an HTTP response for when the user has left a team.
     */
    public function toResponse(Request $request): Response;
}

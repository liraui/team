<?php

namespace LiraUi\Team\Contracts;

use LiraUi\Team\Http\Requests\CreateTeamRequest;
use LiraUi\Team\Models\Team;
use Symfony\Component\HttpFoundation\Response;

interface TeamCreated
{
    /**
     * Create an HTTP response for team creation.
     */
    public function toResponse(CreateTeamRequest $request, Team $team): Response;
}

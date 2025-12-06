<?php

namespace LiraUi\Team\Contracts;

use LiraUi\Team\Http\Requests\UpdateTeamNameRequest;
use Symfony\Component\HttpFoundation\Response;

interface TeamNameUpdated
{
    /**
     * Create an HTTP response for team name update.
     */
    public function toResponse(UpdateTeamNameRequest $request): Response;
}

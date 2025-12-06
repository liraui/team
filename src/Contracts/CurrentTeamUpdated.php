<?php

namespace LiraUi\Team\Contracts;

use LiraUi\Team\Http\Requests\UpdateCurrentTeamRequest;
use Symfony\Component\HttpFoundation\Response;

interface CurrentTeamUpdated
{
    /**
     * Create an HTTP response for current team update.
     */
    public function toResponse(UpdateCurrentTeamRequest $request): Response;
}

<?php

namespace LiraUi\Team\Contracts;

use LiraUi\Team\Http\Requests\DeleteTeamRequest;
use Symfony\Component\HttpFoundation\Response;

interface TeamDeleted
{
    /**
     * Create an HTTP response for team deletion.
     */
    public function toResponse(DeleteTeamRequest $request): Response;
}

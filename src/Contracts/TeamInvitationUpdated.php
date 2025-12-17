<?php

namespace LiraUi\Team\Contracts;

use LiraUi\Team\Http\Requests\UpdateTeamInvitationRequest;
use Symfony\Component\HttpFoundation\Response;

interface TeamInvitationUpdated
{
    /**
     * Create an HTTP response for when a team invitation has been updated.
     */
    public function toResponse(UpdateTeamInvitationRequest $request): Response;
}

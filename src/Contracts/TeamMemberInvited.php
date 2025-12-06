<?php

namespace LiraUi\Team\Contracts;

use LiraUi\Team\Http\Requests\InviteTeamMemberRequest;
use Symfony\Component\HttpFoundation\Response;

interface TeamMemberInvited
{
    /**
     * Create an HTTP response for when a team member has been invited.
     */
    public function toResponse(InviteTeamMemberRequest $request): Response;
}
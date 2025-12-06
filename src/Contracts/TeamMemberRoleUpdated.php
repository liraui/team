<?php

namespace LiraUi\Team\Contracts;

use LiraUi\Team\Http\Requests\UpdateTeamMemberRoleRequest;
use Symfony\Component\HttpFoundation\Response;

interface TeamMemberRoleUpdated
{
    /**
     * Create an HTTP response for when a team member's role has been updated.
     */
    public function toResponse(UpdateTeamMemberRoleRequest $request): Response;
}

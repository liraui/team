<?php

namespace LiraUi\Team\Contracts;

use LiraUi\Team\Http\Requests\CreateTeamRequest;
use LiraUi\Team\Models\Team;

interface CreatesTeam
{
    /**
     * Create a new team.
     */
    public function create(CreateTeamRequest $request): Team;
}

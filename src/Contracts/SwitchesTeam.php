<?php

namespace LiraUi\Team\Contracts;

use LiraUi\Team\Http\Requests\UpdateCurrentTeamRequest;
use LiraUi\Team\Models\Team;

interface SwitchesTeam
{
    /**
     * Switch the current team.
     */
    public function switch(UpdateCurrentTeamRequest $request, Team $team): bool;
}

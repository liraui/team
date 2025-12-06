<?php

namespace LiraUi\Team\Contracts;

use LiraUi\Team\Http\Requests\UpdateCurrentTeamRequest;

interface SwitchesTeam
{
    /**
     * Switch the current team.
     */
    public function switch(UpdateCurrentTeamRequest $request): bool;
}

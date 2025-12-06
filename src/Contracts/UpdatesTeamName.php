<?php

namespace LiraUi\Team\Contracts;

use LiraUi\Team\Http\Requests\UpdateTeamNameRequest;
use LiraUi\Team\Models\Team;

interface UpdatesTeamName
{
    /**
     * Update the name of a team.
     */
    public function update(UpdateTeamNameRequest $request, Team $team): void;
}

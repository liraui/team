<?php

namespace LiraUi\Team\Contracts;

use LiraUi\Team\Http\Requests\DeleteTeamRequest;
use LiraUi\Team\Models\Team;

interface DeletesTeam
{
    /**
     * Delete a team.
     */
    public function delete(DeleteTeamRequest $request, Team $team): void;
}

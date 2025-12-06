<?php

namespace LiraUi\Team\Actions;

use LiraUi\Team\Contracts\UpdatesTeamName;
use LiraUi\Team\Http\Requests\UpdateTeamNameRequest;
use LiraUi\Team\Models\Team;

class UpdateTeamNameAction implements UpdatesTeamName
{
    /**
     * Update the name of the specified team.
     */
    public function update(UpdateTeamNameRequest $request, Team $team): void
    {
        $team->update($request->validated());
    }
}

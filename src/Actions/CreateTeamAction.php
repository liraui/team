<?php

namespace LiraUi\Team\Actions;

use LiraUi\Team\Contracts\CreatesTeam;
use LiraUi\Team\Http\Requests\CreateTeamRequest;
use LiraUi\Team\Models\Team;

class CreateTeamAction implements CreatesTeam
{
    /**
     * Create a new team for the authenticated user.
     */
    public function create(CreateTeamRequest $request): Team
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $team = new Team;

        $team->forceFill([
            'user_id' => $user->id,
            'name' => $request->input('name'),
            'personal_team' => false,
        ])->save();

        return $team;
    }
}

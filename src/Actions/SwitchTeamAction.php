<?php

namespace LiraUi\Team\Actions;

use LiraUi\Team\Contracts\SwitchesTeam;
use LiraUi\Team\Http\Requests\UpdateCurrentTeamRequest;
use LiraUi\Team\Models\Team;
use Illuminate\Validation\ValidationException;

class SwitchTeamAction implements SwitchesTeam
{
    /**
     * Switch the authenticated user's current team.
     */
    public function switch(UpdateCurrentTeamRequest $request): bool
    {
        $team = Team::findOrFail($request->team_id);

        /** @var \App\Models\User $user */
        $user = $request->user();

        if (! $user->switchTeam($team)) {
            throw ValidationException::withMessages([
                'team_id' => [__('The team could not be switched.')],
            ]);
        }

        return true;
    }
}

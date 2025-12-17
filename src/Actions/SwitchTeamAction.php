<?php

namespace LiraUi\Team\Actions;

use Illuminate\Validation\ValidationException;
use LiraUi\Team\Contracts\SwitchesTeam;
use LiraUi\Team\Http\Requests\UpdateCurrentTeamRequest;
use LiraUi\Team\Models\Team;

class SwitchTeamAction implements SwitchesTeam
{
    /**
     * Switch the authenticated user's current team.
     */
    public function switch(UpdateCurrentTeamRequest $request, Team $team): bool
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        if (! $user->switchTeam($team)) {
            throw ValidationException::withMessages([
                'team' => [__('The team could not be switched.')],
            ]);
        }

        return true;
    }
}

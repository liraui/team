<?php

namespace LiraUi\Team\Actions;

use Illuminate\Validation\ValidationException;
use LiraUi\Team\Contracts\DeletesTeam;
use LiraUi\Team\Http\Requests\DeleteTeamRequest;
use LiraUi\Team\Models\Team;

class DeleteTeamAction implements DeletesTeam
{
    /**
     * Delete the specified team.
     */
    public function delete(DeleteTeamRequest $request, Team $team): void
    {
        if ($team->personal_team) {
            throw ValidationException::withMessages([
                'team' => [__('You may not delete your personal team.')],
            ]);
        }

        /** @var \App\Models\User $user */
        $user = $request->user();

        $team->purge();

        if ($user->current_team_id === $team->id) {
            $user->switchTeam($user->personalTeam());
        }
    }
}

<?php

namespace LiraUi\Team\Actions;

use Illuminate\Validation\ValidationException;
use LiraUi\Team\Contracts\DeletesTeam;
use LiraUi\Team\Http\Requests\DeleteTeamRequest;
use LiraUi\Team\Models\Team;
use \App\Models\User;

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

        $usersToReassign = User::where('current_team_id', $team->id)->get();
        
        foreach ($usersToReassign as $userToReassign) {
            $userToReassign->switchTeam($userToReassign->personalTeam());
        }

        $team->purge();
    }
}

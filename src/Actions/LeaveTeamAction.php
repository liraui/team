<?php

namespace LiraUi\Team\Actions;

use App\Models\User;
use Illuminate\Validation\ValidationException;
use LiraUi\Team\Contracts\LeavesTeam;
use LiraUi\Team\Http\Requests\LeaveTeamRequest;
use LiraUi\Team\Models\Team;

class LeaveTeamAction implements LeavesTeam
{
    /**
     * Remove the team member from the given team.
     */
    public function leave(LeaveTeamRequest $request, Team $team, ?User $teamMember = null): void
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $memberToRemove = $teamMember ?? $user;

        $this->ensureMemberBelongsToTeam($memberToRemove, $team);

        $this->ensureMemberDoesNotOwnTeam($memberToRemove, $team);

        $this->removeTeamRoles($memberToRemove, $team);

        $team->users()->detach($memberToRemove);

        $this->switchTeamIfNeeded($memberToRemove, $team);
    }

    /**
     * Remove the member's roles for the given team.
     */
    protected function removeTeamRoles(User $member, Team $team): void
    {
        setPermissionsTeamId($team->id);

        $teamRoles = $member->roles()->get();

        foreach ($teamRoles as $role) {
            $member->removeRole($role);
        }
    }

    /**
     * Ensure that the member belongs to the team.
     */
    protected function ensureMemberBelongsToTeam(User $member, Team $team): void
    {
        if (! $member->belongsToTeam($team)) {
            throw ValidationException::withMessages([
                'team' => [__('This user does not belong to this team.')],
            ]);
        }
    }

    /**
     * Ensure that the member does not own the team.
     */
    protected function ensureMemberDoesNotOwnTeam(User $member, Team $team): void
    {
        if ($member->ownsTeam($team)) {
            throw ValidationException::withMessages([
                'team' => [__('You may not remove the team owner.')],
            ]);
        }
    }

    /**
     * Switch the member to a new team if they are currently on the team they are leaving.
     */
    protected function switchTeamIfNeeded(User $member, Team $team): void
    {
        if ($member->current_team_id !== $team->id) {
            return;
        }

        $member->switchTeam($member->personalTeam());
    }
}

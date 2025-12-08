<?php

namespace LiraUi\Team\Actions;

use LiraUi\Team\Contracts\AddsTeamMember;
use LiraUi\Team\Http\Requests\AcceptTeamInvitationRequest;
use LiraUi\Team\Models\TeamInvitation;
use Spatie\Permission\Models\Role;

class AddTeamMemberAction implements AddsTeamMember
{
    /**
     * Add a new team member to the given team.
     */
    public function add(AcceptTeamInvitationRequest $request, TeamInvitation $invitation): void
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        
        $team = $invitation->team;

        abort_if($user->email !== $invitation->email, 404);

        if (! $team->users()->where('user_id', $user->id)->exists()) {
            $team->users()->attach($user);
        }

        $role = Role::where('id', $invitation->role_id)->where('team_id', $team->id)->first();

        if ($role) {
            setPermissionsTeamId($team->id);
            
            $user->assignRole($role);

            $user->switchTeam($team);
        }
    }
}

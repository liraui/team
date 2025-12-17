<?php

namespace LiraUi\Team\Actions;

use App\Models\User;
use LiraUi\Team\Contracts\UpdatesTeamMemberRole;
use LiraUi\Team\Http\Requests\UpdateTeamMemberRoleRequest;
use LiraUi\Team\Models\Team;
use Spatie\Permission\Models\Role;

class UpdateTeamMemberRoleAction implements UpdatesTeamMemberRole
{
    /**
     * Update the role of a team member.
     */
    public function update(UpdateTeamMemberRoleRequest $request, Team $team, User $teamMember): void
    {
        $validated = $request->validated();

        setPermissionsTeamId($team->id);

        $existingRoles = $teamMember->roles()->get();

        foreach ($existingRoles as $existingRole) {
            $teamMember->removeRole($existingRole);
        }

        $role = Role::where('id', $validated['role_id'])->first();

        if ($role) {
            $teamMember->assignRole($role);
        }
    }
}

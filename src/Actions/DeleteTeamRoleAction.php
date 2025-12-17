<?php

namespace LiraUi\Team\Actions;

use Illuminate\Validation\ValidationException;
use LiraUi\Team\Contracts\DeletesTeamRole;
use LiraUi\Team\Http\Requests\DeleteTeamRoleRequest;
use Spatie\Permission\Models\Role;

class DeleteTeamRoleAction implements DeletesTeamRole
{
    /**
     * Delete the given team role.
     */
    public function delete(DeleteTeamRoleRequest $request, Role $role): void
    {
        if ($role->users()->count() > 0) {
            throw ValidationException::withMessages([
                'role' => [__('Cannot delete a role that has users assigned to it.')],
            ]);
        }

        $team = $request->team;

        $pendingInvitationsCount = $team->teamInvitations()->where('role_id', $role->id)->count();

        if ($pendingInvitationsCount > 0) {
            throw ValidationException::withMessages([
                'role' => [__('Cannot delete a role that has pending invitations.')],
            ]);
        }

        $role->delete();
    }
}

<?php

namespace LiraUi\Team\Actions;

use LiraUi\Team\Contracts\CreatesTeamRole;
use LiraUi\Team\Http\Requests\CreateTeamRoleRequest;
use Spatie\Permission\Models\Role;

class CreateTeamRoleAction implements CreatesTeamRole
{
    /**
     * Create a new team role.
     */
    public function create(CreateTeamRoleRequest $request): Role
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $role = Role::create([
            'name' => $request->name,
            'team_id' => $user->current_team_id,
            'guard_name' => 'web',
        ]);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return $role;
    }
}

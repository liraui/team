<?php

namespace LiraUi\Team\Actions;

use LiraUi\Team\Contracts\CreatesTeamRole;
use LiraUi\Team\Http\Requests\CreateTeamRoleRequest;
use Spatie\Permission\Contracts\Role as RoleContract;
use Spatie\Permission\Models\Role;

class CreateTeamRoleAction implements CreatesTeamRole
{
    /**
     * Create a new team role.
     */
    public function create(CreateTeamRoleRequest $request): RoleContract
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $role = Role::create([
            'name' => $request->input('name'),
            'team_id' => $user->current_team_id,
            'guard_name' => 'web',
        ]);

        if ($request->has('permissions')) {
            $role->syncPermissions((array) $request->input('permissions'));
        }

        return $role;
    }
}

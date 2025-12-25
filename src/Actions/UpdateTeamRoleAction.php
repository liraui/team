<?php

namespace LiraUi\Team\Actions;

use LiraUi\Team\Contracts\UpdatesTeamRole;
use LiraUi\Team\Http\Requests\UpdateTeamRoleRequest;
use Spatie\Permission\Models\Role;

class UpdateTeamRoleAction implements UpdatesTeamRole
{
    /**
     * Update the given team role.
     */
    public function update(UpdateTeamRoleRequest $request, Role $role): void
    {
        $role->update(['name' => $request->input('name')]);

        if ($request->has('permissions')) {
            $role->syncPermissions((array) $request->input('permissions'));
        }
    }
}

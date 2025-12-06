<?php

namespace LiraUi\Team\Contracts;

use LiraUi\Team\Http\Requests\DeleteTeamRoleRequest;
use Spatie\Permission\Models\Role;

interface DeletesTeamRole
{
    /**
     * Delete the given team role.
     */
    public function delete(DeleteTeamRoleRequest $request, Role $role): void;
}

<?php

namespace LiraUi\Team\Contracts;

use LiraUi\Team\Http\Requests\UpdateTeamRoleRequest;
use Spatie\Permission\Models\Role;

interface UpdatesTeamRole
{
    /**
     * Update the given team role.
     */
    public function update(UpdateTeamRoleRequest $request, Role $role): void;
}

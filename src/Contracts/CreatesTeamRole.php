<?php

namespace LiraUi\Team\Contracts;

use LiraUi\Team\Http\Requests\CreateTeamRoleRequest;
use Spatie\Permission\Contracts\Role;

interface CreatesTeamRole
{
    /**
     * Create a new team role.
     */
    public function create(CreateTeamRoleRequest $request): Role;
}

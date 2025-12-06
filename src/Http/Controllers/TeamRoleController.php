<?php

namespace LiraUi\Team\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use LiraUi\Team\Contracts\CreatesTeamRole;
use LiraUi\Team\Contracts\DeletesTeamRole;
use LiraUi\Team\Contracts\TeamRoleCreated;
use LiraUi\Team\Contracts\TeamRoleDeleted;
use LiraUi\Team\Contracts\TeamRoleUpdated;
use LiraUi\Team\Contracts\UpdatesTeamRole;
use LiraUi\Team\Http\Middleware\EnsureTeamPermissionContext;
use LiraUi\Team\Http\Requests\CreateTeamRoleRequest;
use LiraUi\Team\Http\Requests\DeleteTeamRoleRequest;
use LiraUi\Team\Http\Requests\UpdateTeamRoleRequest;
use LiraUi\Team\Models\Team;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\RouteAttributes\Attributes\Delete;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Put;
use Symfony\Component\HttpFoundation\Response;

class TeamRoleController extends Controller
{
    #[Get(
        uri: '/teams/{team}/roles',
        name: 'teams.roles.index',
        middleware: ['web', 'auth', EnsureTeamPermissionContext::class, 'can:view,team']
    )]
    public function showRoles(Request $request, Team $team): InertiaResponse
    {
        $roles = Role::where('team_id', $team->id)->get()->map(fn ($role): array => [
            'id' => $role->id,
            'name' => $role->name,
            'permissions' => $role->permissions->pluck('name'),
        ]);

        $permissions = Permission::all()->pluck('name');

        return Inertia::render('liraui-team::roles/index', [
            'roles' => $roles,
            'availablePermissions' => $permissions,
        ]);
    }

    #[Post(
        uri: '/teams/{team}/roles',
        name: 'teams.roles.create',
        middleware: ['web', 'auth', EnsureTeamPermissionContext::class, 'can:update,team']
    )]
    public function create(CreateTeamRoleRequest $request, Team $team, CreatesTeamRole $creator): Response
    {
        $creator->create($request);

        return app(TeamRoleCreated::class)->toResponse($request);
    }

    #[Put(
        uri: '/teams/{team}/roles/{role}',
        name: 'teams.roles.update',
        middleware: ['web', 'auth', EnsureTeamPermissionContext::class, 'can:update,team']
    )]
    public function updateRole(UpdateTeamRoleRequest $request, Team $team, Role $role, UpdatesTeamRole $updater): Response
    {
        $updater->update($request, $role);

        return app(TeamRoleUpdated::class)->toResponse($request);
    }

    #[Delete(
        uri: '/teams/{team}/roles/{role}',
        name: 'teams.roles.delete',
        middleware: ['web', 'auth', EnsureTeamPermissionContext::class, 'can:update,team']
    )]
    public function delete(DeleteTeamRoleRequest $request, Team $team, Role $role, DeletesTeamRole $deleter): Response
    {
        $deleter->delete($request, $role);

        return app(TeamRoleDeleted::class)->toResponse($request);
    }
}

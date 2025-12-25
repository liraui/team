<?php

namespace LiraUi\Team\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use LiraUi\Team\Contracts\CreatesTeam;
use LiraUi\Team\Contracts\CurrentTeamUpdated;
use LiraUi\Team\Contracts\DeletesTeam;
use LiraUi\Team\Contracts\SwitchesTeam;
use LiraUi\Team\Contracts\TeamCreated;
use LiraUi\Team\Contracts\TeamDeleted;
use LiraUi\Team\Contracts\TeamNameUpdated;
use LiraUi\Team\Contracts\UpdatesTeamName;
use LiraUi\Team\Http\Middleware\EnsureTeamPermissionContext;
use LiraUi\Team\Http\Requests\CreateTeamRequest;
use LiraUi\Team\Http\Requests\DeleteTeamRequest;
use LiraUi\Team\Http\Requests\UpdateCurrentTeamRequest;
use LiraUi\Team\Http\Requests\UpdateTeamNameRequest;
use LiraUi\Team\Http\Resources\TeamResource;
use LiraUi\Team\Models\Team;
use Spatie\Permission\Models\Permission;
use Spatie\RouteAttributes\Attributes\Delete;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Put;
use Symfony\Component\HttpFoundation\Response;

class TeamController extends Controller
{
    #[Get(
        uri: '/teams/create',
        name: 'teams.create',
        middleware: ['web', 'auth']
    )]
    public function showCreateForm(): InertiaResponse
    {
        return Inertia::render('liraui-team::create');
    }

    #[Post(
        uri: '/teams',
        name: 'teams.create.submit',
        middleware: ['web', 'auth', 'can:create,LiraUi\Team\Models\Team']
    )]
    public function createTeam(CreateTeamRequest $request, CreatesTeam $creator): Response
    {
        $team = $creator->create($request);

        /** @var \App\Models\User $user */
        $user = $request->user();

        $user->switchTeam($team);

        return app(TeamCreated::class)->toResponse($request, $team);
    }

    #[Get(
        uri: '/teams/{team}/settings',
        name: 'teams.settings',
        middleware: ['web', 'auth', EnsureTeamPermissionContext::class, 'can:view,team']
    )]
    public function showTeam(Request $request, Team $team): InertiaResponse
    {
        return Inertia::render('liraui-team::settings', [
            'team' => (new TeamResource($team->load([
                'owner',
                'users',
                'roles' => function ($query) {
                    /** @var \Illuminate\Database\Eloquent\Builder<\Spatie\Permission\Models\Role> $query */
                    return $query->select('roles.*');
                },
                'roles.permissions',
                'teamInvitations',
            ])))->resolve(),
            'availablePermissions' => Permission::all()->pluck('name'),
        ]);
    }

    #[Put(
        uri: '/current-team/{team}',
        name: 'current-team.switch',
        middleware: ['web', 'auth', EnsureTeamPermissionContext::class, 'can:switchTeam,team']
    )]
    public function switchTeam(UpdateCurrentTeamRequest $request, Team $team, SwitchesTeam $switcher): Response
    {
        $switcher->switch($request, $team);

        return app(CurrentTeamUpdated::class)->toResponse($request);
    }

    #[Put(
        uri: '/teams/{team}',
        name: 'teams.update',
        middleware: ['web', 'auth', EnsureTeamPermissionContext::class, 'can:update,team']
    )]
    public function updateTeamName(UpdateTeamNameRequest $request, Team $team, UpdatesTeamName $updater): Response
    {
        $updater->update($request, $team);

        return app(TeamNameUpdated::class)->toResponse($request);
    }

    #[Delete(
        uri: '/teams/{team}',
        name: 'teams.delete',
        middleware: ['web', 'auth', EnsureTeamPermissionContext::class, 'can:delete,team']
    )]
    public function deleteTeam(DeleteTeamRequest $request, Team $team, DeletesTeam $deleter): Response
    {
        $deleter->delete($request, $team);

        return app(TeamDeleted::class)->toResponse($request);
    }
}

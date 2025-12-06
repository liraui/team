<?php

namespace LiraUi\Team\Http\Controllers;

use App\Models\User;
use LiraUi\Team\Contracts\LeavesTeam;
use LiraUi\Team\Contracts\TeamLeft;
use LiraUi\Team\Contracts\TeamMemberRemoved;
use LiraUi\Team\Contracts\TeamMemberRoleUpdated;
use LiraUi\Team\Contracts\UpdatesTeamMemberRole;
use LiraUi\Team\Http\Middleware\EnsureTeamPermissionContext;
use LiraUi\Team\Http\Requests\LeaveTeamRequest;
use LiraUi\Team\Http\Requests\UpdateTeamMemberRoleRequest;
use LiraUi\Team\Models\Team;
use Spatie\RouteAttributes\Attributes\Delete;
use Spatie\RouteAttributes\Attributes\Put;
use Symfony\Component\HttpFoundation\Response;

class TeamMemberController extends Controller
{
    #[Put(
        uri: '/teams/{team}/members/{user}',
        name: 'team-members.update',
        middleware: ['web', 'auth', EnsureTeamPermissionContext::class, 'can:updateTeamMember,team']
    )]
    public function updateRole(UpdateTeamMemberRoleRequest $request, Team $team, User $user, UpdatesTeamMemberRole $updater): Response
    {
        $updater->update($request, $team, $user);

        return app(TeamMemberRoleUpdated::class)->toResponse($request);
    }

    #[Delete(
        uri: '/teams/{team}/members/{user}',
        name: 'team-members.remove',
        middleware: ['web', 'auth', EnsureTeamPermissionContext::class]
    )]
    public function remove(LeaveTeamRequest $request, Team $team, User $user, LeavesTeam $leaver): Response
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = $request->user();

        $isLeavingTeam = $currentUser->id === $user->id;

        $leaver->leave($request, $team, $isLeavingTeam ? null : $user);

        if ($isLeavingTeam) {
            return app(TeamLeft::class)->toResponse($request);
        }

        return app(TeamMemberRemoved::class)->toResponse($request);
    }
}

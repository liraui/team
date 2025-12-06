<?php

namespace LiraUi\Team\Http\Controllers;

use Illuminate\Http\Request;
use LiraUi\Team\Contracts\AddsTeamMember;
use LiraUi\Team\Contracts\InvitesTeamMember;
use LiraUi\Team\Contracts\UpdatesTeamInvitation;
use LiraUi\Team\Contracts\TeamMemberInvited;
use LiraUi\Team\Contracts\TeamInvitationDeleted;
use LiraUi\Team\Contracts\TeamInvitationUpdated;
use LiraUi\Team\Contracts\TeamInvitationAccepted;
use LiraUi\Team\Http\Middleware\EnsureTeamPermissionContext;
use LiraUi\Team\Http\Requests\AcceptTeamInvitationRequest;
use LiraUi\Team\Http\Requests\InviteTeamMemberRequest;
use LiraUi\Team\Http\Requests\UpdateTeamInvitationRequest;
use LiraUi\Team\Models\Team;
use LiraUi\Team\Models\TeamInvitation;
use Spatie\RouteAttributes\Attributes\Delete;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Put;
use Symfony\Component\HttpFoundation\Response;

class TeamInvitationController extends Controller
{
    #[Post(
        uri: '/teams/{team}/invitations',
        name: 'team-invitations.invite',
        middleware: ['web', 'auth', EnsureTeamPermissionContext::class, 'can:addTeamMember,team']
    )]
    public function invite(InviteTeamMemberRequest $request, Team $team, InvitesTeamMember $inviter): Response
    {
        $inviter->invite($request, $team);

        return app(TeamMemberInvited::class)->toResponse($request);
    }

    #[Delete(
        uri: '/teams/{team}/invitations/{invitation}',
        name: 'team-invitations.cancel',
        middleware: ['web', 'auth', EnsureTeamPermissionContext::class, 'can:removeTeamMember,team']
    )]
    public function cancel(Request $request, Team $team, TeamInvitation $invitation): Response
    {
        $invitation->delete();

        return app(TeamInvitationDeleted::class)->toResponse($request);
    }

    #[Put(
        uri: '/teams/{team}/invitations/{invitation}',
        name: 'team-invitations.update',
        middleware: ['web', 'auth', EnsureTeamPermissionContext::class, 'can:addTeamMember,team']
    )]
    public function updateRole(UpdateTeamInvitationRequest $request, Team $team, TeamInvitation $invitation, UpdatesTeamInvitation $updater): Response
    {
        $updater->update($request, $invitation);

        return app(TeamInvitationUpdated::class)->toResponse($request);
    }

    #[Get(
        uri: '/team-invitations/{invitation}',
        name: 'team-invitations.accept',
        middleware: ['web', 'auth', 'signed']
    )]
    public function accept(AcceptTeamInvitationRequest $request, TeamInvitation $invitation, AddsTeamMember $adder): Response
    {
        $team = $invitation->team;

        $adder->add($request, $invitation);

        $invitation->delete();

        return app(TeamInvitationAccepted::class)->toResponse($request, $team);
    }
}

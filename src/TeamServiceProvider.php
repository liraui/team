<?php

namespace LiraUi\Team;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use LiraUi\Team\Actions\AddTeamMemberAction;
use LiraUi\Team\Actions\CreateTeamAction;
use LiraUi\Team\Actions\CreateTeamRoleAction;
use LiraUi\Team\Actions\DeleteTeamAction;
use LiraUi\Team\Actions\DeleteTeamRoleAction;
use LiraUi\Team\Actions\InviteTeamMemberAction;
use LiraUi\Team\Actions\LeaveTeamAction;
use LiraUi\Team\Actions\SwitchTeamAction;
use LiraUi\Team\Actions\UpdateTeamInvitationAction;
use LiraUi\Team\Actions\UpdateTeamMemberRoleAction;
use LiraUi\Team\Actions\UpdateTeamNameAction;
use LiraUi\Team\Actions\UpdateTeamRoleAction;
use LiraUi\Team\Contracts\UpdatesTeamMemberRole;
use LiraUi\Team\Contracts\AddsTeamMember;
use LiraUi\Team\Contracts\CreatesTeam;
use LiraUi\Team\Contracts\CreatesTeamRole;
use LiraUi\Team\Contracts\CurrentTeamUpdated;
use LiraUi\Team\Contracts\DeletesTeam;
use LiraUi\Team\Contracts\DeletesTeamRole;
use LiraUi\Team\Contracts\InvitesTeamMember;
use LiraUi\Team\Contracts\LeavesTeam;
use LiraUi\Team\Contracts\SwitchesTeam;
use LiraUi\Team\Contracts\TeamCreated;
use LiraUi\Team\Contracts\TeamDeleted;
use LiraUi\Team\Contracts\TeamLeft;
use LiraUi\Team\Contracts\TeamNameUpdated;
use LiraUi\Team\Contracts\TeamRoleCreated;
use LiraUi\Team\Contracts\TeamRoleDeleted;
use LiraUi\Team\Contracts\TeamRoleUpdated;
use LiraUi\Team\Contracts\TeamMemberInvited;
use LiraUi\Team\Contracts\TeamInvitationDeleted;
use LiraUi\Team\Contracts\TeamInvitationUpdated;
use LiraUi\Team\Contracts\TeamInvitationAccepted;
use LiraUi\Team\Contracts\TeamMemberRemoved;
use LiraUi\Team\Contracts\TeamMemberRoleUpdated;
use LiraUi\Team\Contracts\UpdatesTeamInvitation;
use LiraUi\Team\Contracts\UpdatesTeamName;
use LiraUi\Team\Contracts\UpdatesTeamRole;
use LiraUi\Team\Http\Responses\TeamCreatedResponse;
use LiraUi\Team\Http\Responses\CurrentTeamUpdatedResponse;
use LiraUi\Team\Http\Responses\TeamDeletedResponse;
use LiraUi\Team\Http\Responses\TeamNameUpdatedResponse;
use LiraUi\Team\Http\Responses\TeamRoleCreatedResponse;
use LiraUi\Team\Http\Responses\TeamRoleDeletedResponse;
use LiraUi\Team\Http\Responses\TeamRoleUpdatedResponse;
use LiraUi\Team\Http\Responses\TeamMemberInvitedResponse;
use LiraUi\Team\Http\Responses\TeamInvitationDeletedResponse;
use LiraUi\Team\Http\Responses\TeamInvitationUpdatedResponse;
use LiraUi\Team\Http\Responses\TeamInvitationAcceptedResponse;
use LiraUi\Team\Http\Responses\TeamLeftResponse;
use LiraUi\Team\Http\Responses\TeamMemberRemovedResponse;
use LiraUi\Team\Http\Responses\TeamMemberRoleUpdatedResponse;
use LiraUi\Team\Models\Team;
use LiraUi\Team\Policies\TeamPolicy;

class TeamServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register action bindings
        $this->app->singleton(CreatesTeam::class, CreateTeamAction::class);
        $this->app->singleton(UpdatesTeamName::class, UpdateTeamNameAction::class);
        $this->app->singleton(DeletesTeam::class, DeleteTeamAction::class);
        $this->app->singleton(SwitchesTeam::class, SwitchTeamAction::class);
        $this->app->singleton(CreatesTeamRole::class, CreateTeamRoleAction::class);
        $this->app->singleton(UpdatesTeamRole::class, UpdateTeamRoleAction::class);
        $this->app->singleton(DeletesTeamRole::class, DeleteTeamRoleAction::class);
        $this->app->singleton(InvitesTeamMember::class, InviteTeamMemberAction::class);
        $this->app->singleton(UpdatesTeamInvitation::class, UpdateTeamInvitationAction::class);
        $this->app->singleton(LeavesTeam::class, LeaveTeamAction::class);
        $this->app->singleton(AddsTeamMember::class, AddTeamMemberAction::class);
        $this->app->singleton(UpdatesTeamMemberRole::class, UpdateTeamMemberRoleAction::class);

        // Register response bindings
        $this->app->singleton(TeamCreated::class, TeamCreatedResponse::class);
        $this->app->singleton(TeamNameUpdated::class, TeamNameUpdatedResponse::class);
        $this->app->singleton(TeamDeleted::class, TeamDeletedResponse::class);
        $this->app->singleton(TeamLeft::class, TeamLeftResponse::class);
        $this->app->singleton(CurrentTeamUpdated::class, CurrentTeamUpdatedResponse::class);
        $this->app->singleton(TeamRoleCreated::class, TeamRoleCreatedResponse::class);
        $this->app->singleton(TeamRoleUpdated::class, TeamRoleUpdatedResponse::class);
        $this->app->singleton(TeamRoleDeleted::class, TeamRoleDeletedResponse::class);
        $this->app->singleton(TeamMemberInvited::class, TeamMemberInvitedResponse::class);
        $this->app->singleton(TeamInvitationDeleted::class, TeamInvitationDeletedResponse::class);
        $this->app->singleton(TeamInvitationUpdated::class, TeamInvitationUpdatedResponse::class);
        $this->app->singleton(TeamInvitationAccepted::class, TeamInvitationAcceptedResponse::class);
        $this->app->singleton(TeamMemberRoleUpdated::class, TeamMemberRoleUpdatedResponse::class);
        $this->app->singleton(TeamMemberRemoved::class, TeamMemberRemovedResponse::class);
    }

    /**
     * Register the service provider.
     */
    public function boot(): void
    {
        Gate::policy(Team::class, TeamPolicy::class);

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'liraui-team-migrations');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'liraui-team');
    }
}

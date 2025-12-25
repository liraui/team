<?php

use App\Models\User;
use Illuminate\Support\Facades\URL;
use LiraUi\Team\Database\Seeders\TeamPermissionSeeder;
use LiraUi\Team\Models\Team;
use Spatie\Permission\Models\Role;

test('user can accept a team invitation', function () {
    /** @var \LiraUi\Team\Tests\TestCase $this */
    $this->seed(TeamPermissionSeeder::class);

    $owner = User::factory()->create([
        'email' => 'owner@example.com',
    ]);

    $team = Team::factory()->create([
        'user_id' => $owner->id,
        'name' => 'Team',
        'personal_team' => false,
    ]);

    $invitee = User::factory()->create([
        'email' => 'invitee@example.com',
    ]);

    $role = Role::create([
        'name' => 'Member',
        'team_id' => $team->id,
        'guard_name' => 'web',
    ]);

    $invitation = $team->teamInvitations()->create([
        'email' => $invitee->email,
        'role_id' => $role->id,
    ]);

    $this->actingAs($invitee);

    $url = URL::signedRoute('team-invitations.accept', $invitation);

    $response = $this->get($url);

    $response->assertSessionHas('flash', [
        'type' => 'success',
        'message' => 'Team invitation has been accepted successfully.',
    ]);

    $this->assertDatabaseHas('team_user', [
        'team_id' => $team->id,
        'user_id' => $invitee->id,
    ]);

    $this->assertDatabaseMissing('team_invitations', [
        'id' => $invitation->id,
    ]);
});

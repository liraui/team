<?php

use App\Models\User;
use LiraUi\Team\Database\Seeders\TeamPermissionSeeder;
use LiraUi\Team\Models\Team;
use Spatie\Permission\Models\Role;

test('user can update the role of a team invitation', function () {
    /** @var \LiraUi\Team\Tests\TestCase $this */
    $this->seed(TeamPermissionSeeder::class);

    $user = User::factory()->create([
        'email' => 'test@example.com',
    ]);

    $team = Team::factory()->create([
        'user_id' => $user->id,
        'name' => 'Team',
        'personal_team' => false,
    ]);

    $user->current_team_id = $team->id;

    $user->save();

    $role = Role::create([
        'name' => 'Viewer',
        'team_id' => $team->id,
        'guard_name' => 'web',
    ]);

    $newRole = Role::create([
        'name' => 'Editor',
        'team_id' => $team->id,
        'guard_name' => 'web',
    ]);

    $invitation = $team->teamInvitations()->create([
        'email' => 'invitee@example.com',
        'role_id' => $role->id,
    ]);

    $this->actingAs($user);

    $response = $this->put('/teams/'.$team->id.'/invitations/'.$invitation->id, [
        'role_id' => $newRole->id,
    ]);

    $response->assertSessionHas('flash', [
        'type' => 'success',
        'message' => 'Team invitation has been updated successfully.',
    ]);

    $this->assertDatabaseHas('team_invitations', [
        'id' => $invitation->id,
        'role_id' => $newRole->id,
    ]);
});

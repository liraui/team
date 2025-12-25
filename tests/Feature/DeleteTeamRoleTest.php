<?php

use App\Models\User;
use LiraUi\Team\Models\Team;
use Spatie\Permission\Models\Role;

test('user can delete team role', function () {
    /** @var \LiraUi\Team\Tests\TestCase $this */
    $user = User::factory()->create([
        'email' => 'test@example.com',
    ]);

    $team = Team::factory()->create([
        'user_id' => $user->id,
        'name' => 'Team',
        'personal_team' => false,
    ]);

    $role = Role::create([
        'name' => 'Viewer',
        'team_id' => $team->id,
    ]);

    $this->actingAs($user);

    $response = $this->delete('/teams/'.$team->id.'/roles/'.$role->id);

    $response->assertSessionHas('flash', [
        'type' => 'success',
        'message' => 'Role deleted successfully.',
    ]);

    $this->assertDatabaseMissing('roles', [
        'id' => $role->id,
    ]);
});

test('user cannot delete team role with assigned users', function () {
    /** @var \LiraUi\Team\Tests\TestCase $this */
    $user = User::factory()->create([
        'email' => 'test@example.com',
    ]);

    $team = Team::factory()->create([
        'user_id' => $user->id,
        'name' => 'Team',
        'personal_team' => false,
    ]);

    $role = Role::create([
        'name' => 'Viewer',
        'team_id' => $team->id,
    ]);

    $member = User::factory()->create([
        'email' => 'member@example.com',
    ]);

    $team->users()->attach($member->id);
    
    setPermissionsTeamId($team->id);
    
    $member->assignRole($role);

    $this->actingAs($user);

    $response = $this->delete('/teams/'.$team->id.'/roles/'.$role->id);

    $response->assertSessionHasErrors('role');
});

test('user cannot delete team role with pending invitations', function () {
    /** @var \LiraUi\Team\Tests\TestCase $this */
    $user = User::factory()->create([
        'email' => 'test@example.com',
    ]);

    $team = Team::factory()->create([
        'user_id' => $user->id,
        'name' => 'Team',
        'personal_team' => false,
    ]);

    $role = Role::create([
        'name' => 'Viewer',
        'team_id' => $team->id,
    ]);

    $invitation = $team->teamInvitations()->create([
        'email' => 'invitee@example.com',
        'role_id' => $role->id,
    ]);

    $this->actingAs($user);

    $response = $this->delete('/teams/'.$team->id.'/roles/'.$role->id);

    $response->assertSessionHasErrors('role');
});

test('user cannot delete team role without permission', function () {
    /** @var \LiraUi\Team\Tests\TestCase $this */
    $this->seed(\LiraUi\Team\Database\Seeders\TeamPermissionSeeder::class);

    $user = User::factory()->create([
        'email' => 'test@example.com',
    ]);

    $team = Team::factory()->create([
        'user_id' => $user->id,
        'name' => 'Team',
        'personal_team' => false,
    ]);

    $role = Role::create([
        'name' => 'Editor',
        'team_id' => $team->id,
    ]);

    $team->users()->attach($user->id);

    $user->current_team_id = $team->id;

    $user->save();

    $userRole = Role::create([
        'name' => 'no-update-role',
        'team_id' => $team->id,
    ]);

    $user->roles()->attach($userRole->id, ['team_id' => $team->id]);

    $this->actingAs($user);

    $response = $this->delete('/teams/'.$team->id.'/roles/'.$role->id);

    $response->assertStatus(302);
});

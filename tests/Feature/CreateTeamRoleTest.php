<?php

use App\Models\User;
use LiraUi\Team\Database\Seeders\TeamPermissionSeeder;
use LiraUi\Team\Models\Team;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

test('user can create a team role with no permissions', function () {
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

    $this->actingAs($user);

    $response = $this->post('/teams/'.$team->id.'/roles', [
        'name' => 'Viewer',
    ]);

    $response->assertSessionHas('flash', [
        'type' => 'success',
        'message' => 'Role created successfully.',
    ]);

    $this->assertDatabaseHas('roles', [
        'name' => 'Viewer',
    ]);
});

test('user can create a team role with permissions', function () {
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

    $permission = Permission::create([
        'name' => 'create posts',
        'guard_name' => 'web',
    ]);

    $this->actingAs($user);

    $response = $this->post('/teams/'.$team->id.'/roles', [
        'name' => 'Viewer',
        'permissions' => [$permission->name],
    ]);

    $response->assertSessionHas('flash', [
        'type' => 'success',
        'message' => 'Role created successfully.',
    ]);

    $this->assertDatabaseHas('roles', [
        'name' => 'Viewer',
    ]);

    $role = Role::where('name', 'Viewer')->first();

    $role->load('permissions');

    $this->assertCount(1, $role->permissions);
});

test('user cannot create team role without permission', function () {
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

    $team->users()->attach($user->id);
    
    $user->current_team_id = $team->id;
    
    $user->save();

    $role = Role::create([
        'name' => 'no-update-role',
        'team_id' => $team->id,
    ]);

    $user->roles()->attach($role->id, ['team_id' => $team->id]);

    $this->actingAs($user);

    $response = $this->post('/teams/'.$team->id.'/roles', [
        'name' => 'Viewer',
        'permissions' => ['view team'],
    ]);

    $response->assertStatus(302);
});

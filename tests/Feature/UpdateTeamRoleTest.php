<?php

use App\Models\User;
use LiraUi\Team\Models\Team;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

test('user can update team role name', function () {
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

    $response = $this->put('/teams/'.$team->id.'/roles/'.$role->id, [
        'name' => 'Editor',
    ]);

    $response->assertSessionHas('flash', [
        'type' => 'success',
        'message' => 'Role updated successfully.',
    ]);

    $this->assertDatabaseHas('roles', [
        'name' => 'Editor',
    ]);
});

test('user can update team role permissions', function () {
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

    $permission = Permission::create([
        'name' => 'delete posts',
        'guard_name' => 'web',
    ]);

    $this->actingAs($user);

    $response = $this->put('/teams/'.$team->id.'/roles/'.$role->id, [
        'name' => 'Editor',
        'permissions' => [$permission->name],
    ]);

    $response->assertSessionHas('flash', [
        'type' => 'success',
        'message' => 'Role updated successfully.',
    ]);

    $role->refresh();
    
    $this->assertTrue($role->hasPermissionTo('delete posts'));
});

<?php

use App\Models\User;
use LiraUi\Team\Database\Seeders\TeamPermissionSeeder;
use Spatie\Permission\Models\Role;

test('user can update a team member role', function () {
    /** @var \LiraUi\Team\Tests\TestCase $this */
    $this->seed(TeamPermissionSeeder::class);

    $user = User::factory([
        'email' => 'test@example.com',
    ])->create();

    $team = $user->personalTeam();

    $member = User::factory()->create([
        'email' => 'member@example.com',
    ]);

    $team->users()->attach($member);

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

    setPermissionsTeamId($team->id);

    $member->assignRole($role);

    $this->actingAs($user);

    $response = $this->put('/teams/'.$team->id.'/members/'.$member->id, [
        'role_id' => $newRole->id,
    ]);

    $response->assertSessionHas('flash', [
        'type' => 'success',
        'message' => 'Team member role has been updated successfully.',
    ]);

    $this->assertTrue($member->fresh()->hasRole($newRole->name));
});

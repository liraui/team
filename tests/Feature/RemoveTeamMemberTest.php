<?php

use App\Models\User;
use LiraUi\Team\Database\Seeders\TeamPermissionSeeder;
use LiraUi\Team\Models\Team;
use Spatie\Permission\Models\Role;

test('user can remove a team member', function () {
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

    $member = User::factory()->create([
        'email' => 'member@example.com',
    ]);

    $team->users()->attach($member->id);

    $role = Role::create([
        'name' => 'Member',
        'team_id' => $team->id,
        'guard_name' => 'web',
    ]);

    setPermissionsTeamId($team->id);

    $member->assignRole($role);

    $this->actingAs($owner);

    $response = $this->delete('/teams/'.$team->id.'/members/'.$member->id);

    $response->assertSessionHas('flash', [
        'type' => 'success',
        'message' => 'Team member has been removed successfully.',
    ]);

    $this->assertDatabaseMissing('team_user', [
        'team_id' => $team->id,
        'user_id' => $member->id,
    ]);

    $this->assertFalse($member->fresh()->hasRole($role->name));
});

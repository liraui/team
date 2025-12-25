<?php

use App\Models\User;
use LiraUi\Team\Database\Seeders\TeamPermissionSeeder;
use LiraUi\Team\Http\Resources\TeamResource;
use LiraUi\Team\Models\Team;
use Spatie\Permission\Models\Role;

test('team resource returns correct data', function () {
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

    $resource = new TeamResource($team->load(['owner', 'users', 'roles']));

    $data = $resource->toArray(request());

    expect($data)->toHaveKey('id');
    expect($data)->toHaveKey('name');
    expect($data)->toHaveKey('personal_team');
    expect($data)->toHaveKey('owner');
    expect($data)->toHaveKey('users');
    expect($data)->toHaveKey('roles');
});

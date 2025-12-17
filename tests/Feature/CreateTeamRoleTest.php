<?php

use App\Models\User;
use LiraUi\Team\Database\Seeders\TeamPermissionSeeder;
use LiraUi\Team\Models\Team;

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

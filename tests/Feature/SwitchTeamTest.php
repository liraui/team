<?php

use App\Models\User;
use LiraUi\Team\Database\Seeders\TeamPermissionSeeder;
use LiraUi\Team\Models\Team;

test('user can switch team', function () {
    /** @var \LiraUi\Team\Tests\TestCase $this */
    $this->seed(TeamPermissionSeeder::class);

    $user = User::factory()->create([
        'email' => 'test@example.com',
    ]);

    $team = Team::factory()->create([
        'user_id' => $user->id,
        'name' => 'New Team',
        'personal_team' => false,
    ]);

    $this->actingAs($user);

    $response = $this->put('/current-team/'.$team->id);

    $response->assertSessionHas('flash', [
        'type' => 'success',
        'message' => 'Current team has been updated successfully.',
    ]);

    $this->assertEquals($team->id, $user->fresh()->currentTeam->id);
});

test('user cannot switch into another user\'s team', function () {
    /** @var \LiraUi\Team\Tests\TestCase $this */
    $this->seed(TeamPermissionSeeder::class);

    $user = User::factory()->create([
        'email' => 'test@example.com',
    ]);

    $otherUser = User::factory()->create([
        'email' => 'ahsan@liraui.com',
    ]);

    $team = Team::factory()->create([
        'user_id' => $otherUser->id,
        'name' => 'Other User Team',
        'personal_team' => false,
    ]);

    $this->actingAs($user);

    $response = $this->put('/current-team/'.$team->id);

    $response->assertStatus(403);
});

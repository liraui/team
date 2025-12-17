<?php

use App\Models\User;
use LiraUi\Team\Models\Team;

test('user can update team name', function () {
    /** @var \LiraUi\Team\Tests\TestCase $this */
    $user = User::factory()->create([
        'email' => 'test@example.com',
    ]);

    $team = Team::factory()->create([
        'user_id' => $user->id,
        'name' => 'Team',
        'personal_team' => false,
    ]);

    $this->actingAs($user);

    $response = $this->put('/teams/'.$team->id, [
        'name' => 'New Team Name',
    ]);

    $response->assertSessionHas('flash', [
        'type' => 'success',
        'message' => 'Team name has been updated successfully.',
    ]);

    $this->assertDatabaseHas('teams', [
        'name' => 'New Team Name',
    ]);
});

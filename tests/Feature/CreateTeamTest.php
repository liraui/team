<?php

use App\Models\User;

test('user can create a team', function () {
    /** @var \LiraUi\Team\Tests\TestCase $this */
    $user = User::factory()->create([
        'email' => 'test@example.com',
    ]);

    $this->actingAs($user);

    $response = $this->post('/teams', [
        'name' => 'Test Team',
    ]);

    $response->assertSessionHas('flash', [
        'type' => 'success',
        'message' => 'Team has been created successfully.',
    ]);

    $this->assertDatabaseHas('teams', [
        'name' => 'Test Team',
    ]);
});

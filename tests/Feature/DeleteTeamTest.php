<?php

use App\Models\User;
use LiraUi\Team\Models\Team;

test('user can delete team', function () {
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

    app('router')->get('/dashboard', function () {
        return 'Dashboard';
    })->name('dashboard');

    $response = $this->delete('/teams/'.$team->id);

    $response->assertSessionHas('flash', [
        'type' => 'success',
        'message' => 'Team deleted successfully.',
    ]);

    $this->assertDatabaseMissing('teams', [
        'id' => $team->id,
    ]);
});

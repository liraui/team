<?php

use App\Models\User;
use LiraUi\Team\Models\Team;
use Spatie\Permission\Models\Role;

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

    $member = User::factory()->create([
        'email' => 'member@example.com',
    ]);

    $team->users()->attach($member->id);

    $member->current_team_id = $team->id;

    $member->save();

    $role = Role::create([
        'name' => 'test-role',
        'team_id' => $team->id,
    ]);
    $member->roles()->attach($role->id, ['team_id' => $team->id]);

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

test('user cannot delete personal team', function () {
    /** @var \LiraUi\Team\Tests\TestCase $this */
    $user = User::factory()->create([
        'email' => 'test@example.com',
    ]);

    $team = Team::factory()->create([
        'user_id' => $user->id,
        'name' => 'Personal Team',
        'personal_team' => true,
    ]);

    $this->actingAs($user);

    $response = $this->delete('/teams/'.$team->id);

    $response->assertSessionHasErrors('team');
});

test('user cannot delete team without permission', function () {
    /** @var \LiraUi\Team\Tests\TestCase $this */
    $this->seed(\LiraUi\Team\Database\Seeders\TeamPermissionSeeder::class);

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
        'name' => 'no-delete-role',
        'team_id' => $team->id,
    ]);

    $user->roles()->attach($role->id, ['team_id' => $team->id]);

    $this->actingAs($user);

    app('router')->get('/dashboard', function () {
        return 'Dashboard';
    })->name('dashboard');

    $response = $this->delete('/teams/'.$team->id);

    $response->assertStatus(302);
});

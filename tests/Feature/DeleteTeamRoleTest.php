<?php

use App\Models\User;
use LiraUi\Team\Models\Team;
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

    $response = $this->delete('/teams/'.$team->id.'/roles/'.$role->id);

    $response->assertSessionHas('flash', [
        'type' => 'success',
        'message' => 'Role deleted successfully.',
    ]);

    $this->assertDatabaseMissing('roles', [
        'id' => $role->id,
    ]);
});

<?php

use App\Models\User;
use Illuminate\Support\Facades\Mail;
use LiraUi\Team\Database\Seeders\TeamPermissionSeeder;
use LiraUi\Team\Mail\TeamInvitation;
use LiraUi\Team\Models\Team;
use Spatie\Permission\Models\Role;

test('user can invite a team member', function () {
    /** @var \LiraUi\Team\Tests\TestCase $this */
    $this->seed(TeamPermissionSeeder::class);

    Mail::fake();

    $user = User::factory()->create([
        'email' => 'test@example.com',
    ]);

    $team = Team::factory()->create([
        'user_id' => $user->id,
        'name' => 'Team',
        'personal_team' => false,
    ]);

    $user->current_team_id = $team->id;

    $user->save();

    $role = Role::create([
        'name' => 'Member',
        'team_id' => $team->id,
        'guard_name' => 'web',
    ]);

    $this->actingAs($user);

    $response = $this->post('/teams/'.$team->id.'/invitations', [
        'email' => 'invitee@example.com',
        'role_id' => $role->id,
    ]);

    $response->assertSessionHas('flash', [
        'type' => 'success',
        'message' => 'Team member has been invited successfully.',
    ]);

    $this->assertDatabaseHas('team_invitations', [
        'email' => 'invitee@example.com',
        'team_id' => $team->id,
        'role_id' => $role->id,
    ]);

    Mail::assertSent(TeamInvitation::class, function ($mail) {
        return $mail->hasTo('invitee@example.com');
    });
});

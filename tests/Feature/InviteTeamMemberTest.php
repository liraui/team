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

test('user cannot invite team member without permission', function () {
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

    $team->users()->attach($user->id);

    $user->current_team_id = $team->id;

    $user->save();

    $role = Role::create([
        'name' => 'no-invite-role',
        'team_id' => $team->id,
    ]);

    $user->roles()->attach($role->id, ['team_id' => $team->id]);

    $anotherRole = Role::create([
        'name' => 'member-role',
        'team_id' => $team->id,
    ]);

    $anotherRole->givePermissionTo(['view team']);

    $this->actingAs($user);

    $response = $this->post('/teams/'.$team->id.'/invitations', [
        'email' => 'invitee@example.com',
        'role' => $anotherRole->name,
    ]);

    $response->assertStatus(302);
});

<?php

use App\Models\User;
use LiraUi\Team\Models\Team;

test('user has teams methods', function () {
    /** @var \LiraUi\Team\Tests\TestCase $this */
    $user = User::factory()->create([
        'email' => 'test@example.com',
    ]);

    $team = Team::factory()->create([
        'user_id' => $user->id,
        'name' => 'Team',
        'personal_team' => false,
    ]);

    $user->teams()->attach($team->id);

    expect($user->belongsToTeam($team))->toBeTrue();

    expect($user->ownsTeam($team))->toBeTrue();

    $result = $user->switchTeam($team);

    expect($result)->toBeTrue();

    $otherTeam = Team::factory()->create([
        'name' => 'Other Team',
        'personal_team' => false,
    ]);

    $result2 = $user->switchTeam($otherTeam);

    expect($result2)->toBeFalse();

    $allTeams = $user->allTeams();

    expect($allTeams)->toHaveCount(2);

    $allTeamsAttr = $user->all_teams;

    expect($allTeamsAttr)->toHaveCount(2);

    $personal = $user->personalTeam();

    expect($personal->personal_team)->toBeTrue();

    $current = $user->currentTeam;

    expect($current)->toBeInstanceOf(Team::class);

    $owned = $user->ownedTeams;

    expect($owned)->toHaveCount(2);

    $ownedQuery = $user->ownedTeams();

    expect($ownedQuery)->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);

    $teamsQuery = $user->teams();

    expect($teamsQuery)->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsToMany::class);

    $currentAttr = $user->getCurrentTeamAttribute();

    expect($currentAttr)->toBeInstanceOf(Team::class);

    expect($user->hasTeamPermission($team, 'view team'))->toBeTrue();

    $abilities = $user->teamAbilitiesFor($team);

    expect($abilities)->toBeArray();
    
    expect($abilities)->toHaveKey('canViewTeam');

    $currentAbilities = $user->teamAbilities;

    expect($currentAbilities)->toBeArray();
});

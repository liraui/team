<?php

use App\Models\User;
use LiraUi\Team\Policies\TeamPolicy;

test('team policy viewAny returns true', function () {
    $user = User::factory()->create();
    $policy = new TeamPolicy;

    $result = $policy->viewAny($user);

    expect($result)->toBeTrue();
});

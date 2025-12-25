<?php

namespace LiraUi\Team\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTeamPermissionContext
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $team = $request->route('team');

        if ($team instanceof \LiraUi\Team\Models\Team) {
            setPermissionsTeamId($team->id);

            $response = $next($request);

            /** @var Response $response */
            return $response;
        }

        /** @var \App\Models\User|null $user */
        $user = $request->user();

        if ($user && $user->current_team_id) {
            setPermissionsTeamId($user->current_team_id);
        }

        $response = $next($request);

        /** @var Response $response */
        return $response;
    }
}

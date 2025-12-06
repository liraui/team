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
        if ($team = $request->route('team')) {
            setPermissionsTeamId($team->id);

            return $next($request);
        }

        /** @var \App\Models\User|null $user */
        $user = $request->user();

        if ($user && $user->current_team_id) {
            setPermissionsTeamId($user->current_team_id);
        }

        return $next($request);
    }
}

<?php

namespace LiraUi\Team\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \LiraUi\Team\Models\Team
 */
class TeamResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var \LiraUi\Team\Models\Team $team */
        $team = $this->resource;

        return [
            'id' => $team->getKey(),
            'name' => $team->getAttribute('name'),
            'personal_team' => (bool) $team->getAttribute('personal_team'),
            'avatar' => $team->getAttribute('avatar'),
            'created_at' => $team->getAttribute('created_at'),
            'updated_at' => $team->getAttribute('updated_at'),
            'owner' => $this->whenLoaded('owner'),
            'users' => $this->whenLoaded('users', function () use ($team) {
                /** @var \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users */
                $users = $team->getRelationValue('users');

                return $users->map(function (\App\Models\User $user) {
                    $role = $user->roles()->first();

                    return [
                        'id' => $user->getKey(),
                        'name' => $user->getAttribute('name'),
                        'email' => $user->getAttribute('email'),
                        'avatar' => $user->getAttribute('avatar'),
                        'role' => $role ? [
                            'id' => $role->getKey(),
                            'name' => $role->getAttribute('name'),
                        ] : null,
                    ];
                });
            }),
            'roles' => $this->whenLoaded('roles'),
            'team_invitations' => $this->whenLoaded('teamInvitations', function () use ($team) {
                $invitations = $team->getRelationValue('teamInvitations');

                /** @var \Illuminate\Database\Eloquent\Collection<int, \LiraUi\Team\Models\TeamInvitation> $invitations */
                return $invitations->map(function (\LiraUi\Team\Models\TeamInvitation $invitation) {
                    return [
                        'id' => $invitation->getKey(),
                        'team_id' => $invitation->getAttribute('team_id'),
                        'email' => $invitation->getAttribute('email'),
                        'role_id' => $invitation->getAttribute('role_id'),
                        'role' => $invitation->role ? [
                            'id' => $invitation->role->getKey(),
                            'name' => $invitation->role->getAttribute('name'),
                        ] : null,
                        'created_at' => $invitation->getAttribute('created_at'),
                        'updated_at' => $invitation->getAttribute('updated_at'),
                    ];
                });
            }),
        ];
    }
}

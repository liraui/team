<?php

namespace LiraUi\Team\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'personal_team' => $this->personal_team,
            'avatar' => $this->avatar,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'owner' => $this->whenLoaded('owner'),
            'users' => $this->whenLoaded('users', function () {
                return $this->users->map(function ($user) {
                    $role = $user->roles()->first();

                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'avatar' => $user->avatar,
                        'role' => $role ? [
                            'id' => $role->id,
                            'name' => $role->name,
                        ] : null,
                    ];
                });
            }),
            'roles' => $this->whenLoaded('roles'),
            'team_invitations' => $this->whenLoaded('teamInvitations', function () {
                return $this->teamInvitations->map(function ($invitation) {
                    return [
                        'id' => $invitation->id,
                        'team_id' => $invitation->team_id,
                        'email' => $invitation->email,
                        'role_id' => $invitation->role_id,
                        'role' => $invitation->role ? [
                            'id' => $invitation->role->id,
                            'name' => $invitation->role->name,
                        ] : null,
                        'created_at' => $invitation->created_at,
                        'updated_at' => $invitation->updated_at,
                    ];
                });
            }),
        ];
    }
}

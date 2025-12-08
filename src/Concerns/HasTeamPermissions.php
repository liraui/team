<?php

namespace LiraUi\Team\Concerns;

use Illuminate\Database\Eloquent\Casts\Attribute;
use LiraUi\Team\Models\Team;
use Spatie\Permission\Traits\HasRoles;

trait HasTeamPermissions
{
    use HasRoles;

    /**
     * Get the user's abilities for the given team.
     */
    public function teamAbilitiesFor(Team $team): array
    {
        return [
            'canViewTeam' => $this->can('view', $team),
            'canAddTeamMembers' => $this->can('addTeamMember', $team),
            'canDeleteTeam' => $this->can('delete', $team),
            'canUpdateTeam' => $this->can('update', $team),
            'canUpdateTeamMember' => $this->can('updateTeamMember', $team),
            'canRemoveTeamMember' => $this->can('removeTeamMember', $team),
            'canLeaveTeam' => $this->can('leave', $team),
        ];
    }

    /**
     * Get the user's abilities for their current team.
     */
    protected function teamAbilities(): Attribute
    {
        return Attribute::get(function (): array {
            $team = $this->currentTeam;

            if (! $team) {
                return [];
            }

            return $this->teamAbilitiesFor($team);
        });
    }
}

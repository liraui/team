<?php

namespace LiraUi\Team\Concerns;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use LiraUi\Team\Models\Team;
use Spatie\Permission\Models\Role;

trait HasTeams
{

    /**
     * Boot the HasTeams trait.
     */
    public static function bootHasTeams()
    {
        static::created(function ($user) {
            $user->createPersonalTeam();
        });
    }

    /**
     * Determine if the given team is the current team.
     */
    public function isCurrentTeam(Team $team): bool
    {
        return $team->id === $this->current_team_id;
    }

    /**
     * Get the current team of the user's context.
     */
    public function currentTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'current_team_id');
    }

    /**
     * Switch the user's context to the given team.
     */
    public function switchTeam(Team $team): bool
    {
        if (! $this->belongsToTeam($team)) {
            return false;
        }

        $this->forceFill([
            'current_team_id' => $team->id,
        ])->save();

        $this->setRelation('currentTeam', $team);

        return true;
    }

    /**
     * Get all of the teams the user owns or belongs to.
     */
    public function allTeams(): Collection
    {
        return $this->ownedTeams->merge($this->teams)->sortBy('name');
    }

    /**
     * Get all of the teams the user owns or belongs to as an attribute.
     */
    public function getAllTeamsAttribute(): Collection
    {
        return $this->allTeams()->values();
    }

    /**
     * Get all of the teams the user owns.
     */
    public function ownedTeams(): HasMany
    {
        return $this->hasMany(Team::class);
    }

    /**
     * Get all of the teams the user belongs to.
     */
    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'team_user')->withTimestamps();
    }

    /**
     * Get the user's personal team.
     */
    public function personalTeam(): ?Team
    {
        return $this->ownedTeams->where('personal_team', true)->first();
    }

    /**
     * Determine if the user owns the given team.
     */
    public function ownsTeam(Team $team): bool
    {
        return $this->id == $team->user_id;
    }

    /**
     * Determine if the user belongs to the given team.
     */
    public function belongsToTeam(Team $team): bool
    {
        return $this->ownsTeam($team) || $this->teams->contains($team);
    }

    /**
     * Get the current team of the user's context as an attribute.
     */
    public function getCurrentTeamAttribute(): Team
    {
        return $this->getRelationValue('currentTeam');
    }

    /**
     * Create a personal team for the user.
     */
    public function createPersonalTeam()
    {
        $name = explode(' ', $this->name, 2)[0]."'s Team";

        $team = Team::forceCreate([
            'user_id' => $this->id,
            'name' => $name,
            'personal_team' => true,
        ]);

        $this->forceFill([
            'current_team_id' => $team->id,
        ])->save();

        $this->setRelation('currentTeam', $team);

        return $team;
    }

    /**
     * Check if the user has the given permission on the given team.
     */
    public function hasTeamPermission(Team $team, string $permission): bool
    {
        if ($this->ownsTeam($team)) {
            return true;
        }

        if (! $this->belongsToTeam($team)) {
            return false;
        }

        setPermissionsTeamId($team->id);

        return $this->hasPermissionTo($permission);
    }
}

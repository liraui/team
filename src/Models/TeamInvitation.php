<?php

namespace LiraUi\Team\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Permission\Models\Role;

/**
 * @property int $id
 * @property int $team_id
 * @property int $role_id
 * @property string $email
 * @property \LiraUi\Team\Models\Team $team
 * @property \Spatie\Permission\Models\Role|null $role
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 */
class TeamInvitation extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'role_id',
    ];

    /**
     * Get the team that the invitation belongs to.
     *
     * @return BelongsTo<\LiraUi\Team\Models\Team, $this>
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Get the role associated with the invitation.
     *
     * @return BelongsTo<\Spatie\Permission\Models\Role, $this>
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
}

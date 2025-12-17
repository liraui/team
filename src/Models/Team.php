<?php

namespace LiraUi\Team\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravolt\Avatar\Avatar;

class Team extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'personal_team',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'personal_team' => 'boolean',
    ];

    /**
     * The attributes that should be appended.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'avatar',
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return TFactory|null
     */
    protected static function newFactory()
    {
        return \LiraUi\Team\Tests\Database\Factories\TeamFactory::new();
    }

    /**
     * Get the owner of the team.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get all of the users that belong to the team.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'team_user')->withTimestamps();
    }

    /**
     * Get all roles for the team.
     */
    public function roles(): HasMany
    {
        return $this->hasMany(\Spatie\Permission\Models\Role::class);
    }

    /**
     * Get all of the pending invitations for the team.
     */
    public function teamInvitations(): HasMany
    {
        return $this->hasMany(TeamInvitation::class);
    }

    /**
     * Get the team's avatar.
     */
    protected function avatar(): Attribute
    {
        return Attribute::get(function (): string {
            $avatar = app(Avatar::class);

            return $avatar->create($this->name)->toGravatar(['d' => 'initials', 'r' => 'g', 's' => 100]);
        });
    }

    /**
     * Purge all of the team's resources and delete the team.
     */
    public function purge(): void
    {
        setPermissionsTeamId($this->id);

        $this->users->each(function ($member) {
            $teamRoles = $member->roles()->get();

            foreach ($teamRoles as $role) {
                $member->removeRole($role);
            }
        });

        $this->users()->detach();

        $this->roles()->delete();

        $this->teamInvitations()->delete();

        $this->delete();
    }
}

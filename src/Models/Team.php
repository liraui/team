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

/**
 * @property int $id
 * @property string $name
 * @property bool $personal_team
 * @property string $avatar
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property \Illuminate\Database\Eloquent\Collection<int, \LiraUi\Team\Models\TeamInvitation> $teamInvitations
 *
 * @use HasFactory<\LiraUi\Team\Tests\Database\Factories\TeamFactory>
 */
class Team extends Model
{
    /** @use HasFactory<\LiraUi\Team\Tests\Database\Factories\TeamFactory> */
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
     * @return \LiraUi\Team\Tests\Database\Factories\TeamFactory|null
     */
    protected static function newFactory()
    {
        return \LiraUi\Team\Tests\Database\Factories\TeamFactory::new();
    }

    /**
     * Get the owner of the team.
     *
     * @return BelongsTo<\App\Models\User, $this>
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get all of the users that belong to the team.
     *
     * @return BelongsToMany<\App\Models\User, $this>
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'team_user')->withTimestamps();
    }

    /**
     * Get all roles for the team.
     *
     * @return HasMany<\Spatie\Permission\Models\Role, $this>
     */
    public function roles(): HasMany
    {
        return $this->hasMany(\Spatie\Permission\Models\Role::class);
    }

    /**
     * Get all of the pending invitations for the team.
     *
     * @return HasMany<\LiraUi\Team\Models\TeamInvitation, $this>
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

            /** @var string $name */
            $name = $this->getAttribute('name') ?? '';

            return $avatar->create($name)->toGravatar(['d' => 'initials', 'r' => 'g', 's' => 100]);
        });
    }

    /**
     * Purge all of the team's resources and delete the team.
     */
    public function purge(): void
    {
        setPermissionsTeamId($this->id);

        $this->users->each(function (User $member) {
            $teamRoles = $member->roles()->get();

            foreach ($teamRoles as $role) {
                /** @var \Spatie\Permission\Models\Role $role */
                $member->removeRole($role);
            }
        });

        $this->users()->detach();

        $this->roles()->delete();

        $this->teamInvitations()->delete();

        $this->delete();
    }
}

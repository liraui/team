<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravolt\Avatar\Avatar;
use LiraUi\Auth\Concerns\HasEmailVerification;
use LiraUi\Team\Concerns\HasTeamPermissions;
use LiraUi\Team\Concerns\HasTeams;

/**
 * @property int $id
 * @property string $name
 * @property string $avatar
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property \Carbon\Carbon|null $two_factor_confirmed_at
 * @property int|null $current_team_id
 * @property \LiraUi\Team\Models\Team|null $currentTeam
 *
 * @method \Illuminate\Database\Eloquent\Relations\BelongsToMany roles()
 * @method void removeRole(\Spatie\Permission\Models\Role $role)
 * @method void assignRole(mixed $role)
 * @method bool switchTeam(mixed $team)
 * @method bool belongsToTeam(mixed $team)
 * @method bool ownsTeam(mixed $team)
 * @method \LiraUi\Team\Models\Team personalTeam()
 */
class User extends Authenticatable
{
    /** @use HasFactory<\LiraUi\Team\Tests\Database\Factories\UserFactory> */
    use HasEmailVerification,
        HasFactory,
        HasTeamPermissions,
        HasTeams,
        Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be appended.
     *
     * @var list<string>
     */
    protected $appends = [
        'name',
        'avatar',
        'all_teams',
        'current_team',
        'team_abilities',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return TFactory|null
     */
    protected static function newFactory()
    {
        return \LiraUi\Team\Tests\Database\Factories\UserFactory::new();
    }

    /**
     * Get the user's name attribute.
     */
    protected function name(): Attribute
    {
        return Attribute::get(function (): string {
            return $this->first_name.' '.$this->last_name;
        });
    }

    /**
     * Get the user's avatar.
     */
    protected function avatar(): Attribute
    {
        return Attribute::get(function (): string {
            $avatar = new Avatar([
                'shape' => 'square',
                'chars' => 1,
                'width' => 100,
                'height' => 100,
                'fontSize' => 48,
                'backgrounds' => [
                    '#4B8BBE',
                    '#306998',
                    '#FFE873',
                    '#FFD43B',
                    '#646464',
                    '#FF6F61',
                    '#6A4C93',
                    '#20B2AA',
                    '#FFB347',
                    '#8FBC8F',
                ],
                'foregrounds' => [
                    '#FFFFFF',
                    '#FFFFFF',
                    '#333333',
                    '#333333',
                    '#FFFFFF',
                    '#FFFFFF',
                    '#FFFFFF',
                    '#FFFFFF',
                    '#333333',
                    '#FFFFFF',
                ],
                'border' => [
                    'size' => 0,
                ],
            ]);

            return $avatar->create($this->name)->toBase64();
        });
    }
}

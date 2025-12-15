<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravolt\Avatar\Avatar;
use LiraUi\Auth\Concerns\HasEmailVerification;

class User extends Authenticatable
{
    use HasEmailVerification;
    use HasFactory;
    use Notifiable;

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
        return \LiraUi\Auth\Tests\Database\Factories\UserFactory::new();
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

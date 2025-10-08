<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'last_login_at',
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
        ];
    }

    // Token management methods
    public function createAuthToken(string $name = 'auth-token', array $abilities = ['*'])
    {
        return $this->createToken($name, $abilities);
    }

    public function revokeAllTokens()
    {
        return $this->tokens()->delete();
    }

    public function revokeCurrentToken()
    {
        return $this->currentAccessToken()?->delete();
    }

    // Helper methods
    public function updateLastLogin()
    {
        $this->update(['last_login_at' => now()]);
    }

    public function isActive(): bool
    {
        return $this->email_verified_at !== null;
    }
}

<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // <--- ACTUALIZADO: Permitimos la asignación masiva del rol
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
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

    // =========================================================================
    // MÉTODOS DE CONTROL DE ROLES (Evitan el error en navigation-menu.blade.php)
    // =========================================================================

    /**
     * Comprobar si el usuario tiene rol PERSONAL
     */
    public function isPersonal(): bool
    {
        return $this->role === 'PERSONAL';
    }

    /**
     * Comprobar si el usuario tiene rol FAMILIAR
     */
    public function isFamiliar(): bool
    {
        return $this->role === 'FAMILIAR';
    }

    /**
     * Comprobar si el usuario tiene rol EMPRESARIAL
     */
    public function isEmpresarial(): bool
    {
        return $this->role === 'EMPRESARIAL';
    }
}
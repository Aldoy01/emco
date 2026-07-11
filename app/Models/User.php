<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    public const ROLE_SUPER_ADMIN = 'super_admin';
    public const ROLE_FINANCE = 'finance';
    public const ROLE_SHIPPING = 'shipping';

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'admin_role',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin' => 'boolean',
    ];

    public static function adminRoles(): array
    {
        return [
            self::ROLE_SUPER_ADMIN => 'Super Admin',
            self::ROLE_FINANCE => 'Finance',
            self::ROLE_SHIPPING => 'Pengiriman',
        ];
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function isAdmin(): bool
    {
        return (bool) $this->is_admin;
    }

    public function adminRole(): string
    {
        if (! $this->isAdmin()) {
            return 'member';
        }

        return $this->admin_role ?: self::ROLE_SUPER_ADMIN;
    }

    public function roleLabel(): string
    {
        if (! $this->isAdmin()) {
            return 'Member Pembeli';
        }

        return self::adminRoles()[$this->adminRole()] ?? 'Admin';
    }

    public function hasAdminRole(array|string $roles): bool
    {
        if (! $this->isAdmin()) {
            return false;
        }

        $roles = is_array($roles) ? $roles : func_get_args();

        return in_array($this->adminRole(), $roles, true);
    }
}
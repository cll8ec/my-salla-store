<?php

declare(strict_types=1);

namespace App\Domains\Identity\Models;

use App\Domains\Identity\Enums\RoleCode;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Passkeys\PasskeyAuthenticatable;
use Laravel\Passkeys\Contracts\PasskeyUser;

class User extends Authenticatable implements PasskeyUser
{
    use HasFactory;
    use Notifiable;
    use SoftDeletes;
    use TwoFactorAuthenticatable;
    use PasskeyAuthenticatable;

    protected $table = 'users';

    protected $fillable = [
        'salla_customer_id',
        'name',
        'email',
        'phone',
        'password',
        'avatar_url',
        'locale',
        'preferences',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'preferences' => 'array',
        ];
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user')
            ->withPivot(['assigned_at', 'assigned_by_id']);
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(\App\Domains\Wishlist\Models\Wishlist::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(\App\Domains\Reviews\Models\Review::class);
    }

    public function media(): MorphMany
    {
        return $this->morphMany(\App\Domains\Media\Models\Media::class, 'mediable');
    }

    public function scopeRole($query, string $code)
    {
        return $query->whereHas('roles', fn ($q) => $q->where('code', $code));
    }

    public function hasRole(RoleCode|string $code): bool
    {
        $needle = $code instanceof RoleCode ? $code->value : $code;
        return $this->relationLoaded('roles')
            ? $this->roles->contains('code', $needle)
            : $this->roles()->where('code', $needle)->exists();
    }

    public function hasAnyRole(array $codes): bool
    {
        $values = array_map(
            fn ($c) => $c instanceof RoleCode ? $c->value : $c,
            $codes,
        );
        return $this->relationLoaded('roles')
            ? $this->roles->whereIn('code', $values)->isNotEmpty()
            : $this->roles()->whereIn('code', $values)->exists();
    }

    public function assignRole(RoleCode|string $code, ?int $assignedById = null): void
    {
        $needle = $code instanceof RoleCode ? $code->value : $code;
        $role = Role::where('code', $needle)->first();
        if ($role) {
            $this->roles()->syncWithoutDetaching([
                $role->id => [
                    'assigned_at' => now(),
                    'assigned_by_id' => $assignedById,
                ]
            ]);
        }
    }
}

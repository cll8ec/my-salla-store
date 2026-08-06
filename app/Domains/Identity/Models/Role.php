<?php

declare(strict_types=1);

namespace App\Domains\Identity\Models;

use App\Domains\Identity\Enums\RoleCode;
use App\Shared\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string|null $description
 * @property int $level
 * @property bool $is_default
 * @property bool $is_protected
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Collection<int, User> $users
 * @property-read Collection<int, Permission> $permissions
 *
 * @mixin Builder
 */
class Role extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'level',
        'is_default',
        'is_protected',
    ];

    protected $hidden = [
        'permissions',
    ];

    protected function casts(): array
    {
        return [
            'level' => 'integer',
            'is_default' => 'boolean',
            'is_protected' => 'boolean',
        ];
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'role_user')
            ->withPivot(['assigned_at', 'assigned_by_id']);
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'permission_role');
    }

    public function codeEnum(): RoleCode
    {
        return RoleCode::from($this->code);
    }

    public function scopeDefault(Builder $query): Builder
    {
        return $query->where('is_default', true);
    }

    public function scopeProtected(Builder $query): Builder
    {
        return $query->where('is_protected', true);
    }

    public function scopeStaff(Builder $query): Builder
    {
        return $query->where('code', '!=', RoleCode::Customer->value);
    }

    public function scopeByCode(Builder $query, string $code): Builder
    {
        return $query->where('code', $code);
    }

    public function scopeMinLevel(Builder $query, int $level): Builder
    {
        return $query->where('level', '>=', $level);
    }

    public function hasPermission(string $permissionCode): bool
    {
        return $this->permissions()->where('code', $permissionCode)->exists();
    }
}

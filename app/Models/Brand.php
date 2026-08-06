<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\SyncStatus;
use Carbon\Carbon;
use Database\Factories\BrandFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $salla_id
 * @property string $name
 * @property string $slug
 * @property string|null $logo
 * @property string|null $description
 * @property SyncStatus $sync_status
 * @property Carbon|null $synced_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
final class Brand extends Model
{
    /** @use HasFactory<BrandFactory> */
    use HasFactory;

    protected $table = 'brands';

    protected $fillable = [
        'salla_id',
        'name',
        'slug',
        'logo',
        'description',
        'sync_status',
        'synced_at',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'sync_status' => SyncStatus::class,
        'synced_at' => 'datetime',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function scopeSynced($query)
    {
        return $query->where('sync_status', SyncStatus::SYNCED);
    }
}

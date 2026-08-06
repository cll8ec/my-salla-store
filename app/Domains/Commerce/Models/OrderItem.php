<?php

declare(strict_types=1);

namespace App\Domains\Commerce\Models;

use App\Domains\Catalog\Models\Product;
use App\Domains\Catalog\Models\ProductVariant;
use App\Shared\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'salla_id',
        'order_id',
        'product_id',
        'product_variant_id',
        'name',
        'sku',
        'quantity',
        'unit_price_minor',
        'total_minor',
        'options',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'unit_price_minor' => 'integer',
            'total_minor' => 'integer',
            'options' => 'array',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $appends = ['stock'];

    protected static function booted(): void
    {
        static::deleting(function (Product $product) {
            $product->productAccounts()->delete();
        });
    }

    /**
     * 在庫数取得
     *
     * @return int
     */
    public function getStockAttribute(): int
    {
        if ($this->relationLoaded('productAccounts')) {
            return $this->productAccounts->where('is_used', false)->count();
        }

        return $this->productAccounts()->where('is_used', false)->count();
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return HasMany
     */
    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * @return HasMany
     */
    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }

    /**
     * @return HasMany
     */
    public function productAccounts(): HasMany
    {
        return $this->hasMany(ProductAccount::class);
    }
}

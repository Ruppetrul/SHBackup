<?php

namespace Modules\Mini\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Mini\Models\Enums\ProductStatusEnum;

class Product extends Model
{
    /**
     * Set column in fillable.
     *
     * @var array
     */
    protected $fillable = [
        'vendor_id',
        'first_media_id',
        'title',
        'slug',
        'sku',
        'price',
        'quantity',
        'type',
        'short_description',
        'body',
        'status',
        'is_popular',
    ];

    public function avatar()
    {
        return $this->hasMany(Media::class, 'item_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories', 'product_id', 'category_id');
    }

    /**
     * Scope active status.
     *
     * @param $query
     *
     * @return mixed
     */
    public function scopeActive($query)
    {
        return $query->where('status', ProductStatusEnum::STATUS_ACTIVE->value);
    }

    /**
     * Get product price.
     *
     * @return string
     */
    public function getPrice()
    {
        return number_format($this->price);
    }

    public function medias()
    {
        return $this->hasMany(Media::class, 'item_id');
    }
}

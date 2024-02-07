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

    /**
     * Relation to Category model, many to many.
     *
     * @return BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_category');
    }
}

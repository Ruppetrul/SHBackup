<?php

namespace Modules\Mini\Repositories;

use Modules\Mini\Models\Product;

class ProductRepoEloquent implements ProductRepoEloquentInterface
{
    /**
     * Find product by sku & slug.
     *
     * @param $id
     * @param $slug
     *
     * @return mixed
     */
    public function findProductById($id)
    {
        return Product::query()
            ->with(['categories'])
            ->active()
            ->where('id', (int) $id)
            ->firstOrFail();
    }
}

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
//            ->with(['categories'])
            ->active()
            ->where('id', (int) $id)
            ->firstOrFail();
    }

    /**
     * Get active products.
     *
     * @param int $pageSize
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getActive($filter)
    {
        $pageSize = 10; //TODO move to env
        $query = Product::query()->active();

        if (isset($filter['search'])) {
            $search = $filter['search'];
            $query->where('title', 'like', "%$search%");
        }

        return $query->latest()
            ->paginate($pageSize);
    }
}

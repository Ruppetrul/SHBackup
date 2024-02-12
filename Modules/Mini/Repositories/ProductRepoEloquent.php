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
    public function getActive($params)
    {
        $pageSize = 10; //TODO move to env
        $query = Product::query()->active();

        if (isset($params['search'])) {
            $search = $params['search'];
            $query->where('title', 'like', "%$search%");
        }

        if (isset($params['priority_filter'])) {
            $filter = $params['priority_filter'];
            switch ($filter) {
                case 'new': //TODO check it when datetime fields will be fixed
                    $query->latest();
                    break;
                case 'old': //TODO check it when datetime fields will be fixed
                    $query->oldest();
                    break;
                case 'expensive':
                    $query->orderBy('price', 'desc');
                    break;
                case 'cheap':
                    $query->orderBy('price', 'asc');
                    break;
                default:
                    break;
            }
        }

        return $query->paginate($pageSize);
    }
}

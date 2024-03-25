<?php

namespace Modules\Mini\Repositories;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Mini\Models\Product;

class MiniRepoEloquent implements MiniRepoEloquentInterface
{
    /**
     * Get latest active products.
     *
     * @return mixed
     */
    public static function getLatestActiveProducts()
    {
        return Product::query()
            ->active()
            ->latest()
            ->limit(10)
            ->get();
    }

    /**
     * @return array
     */
    public static function getCartData($cart_id = null)
    {
        $cart = DB::table('cart')
            ->where('ip_address', $_SERVER['REMOTE_ADDR'])
            ->where('status', 0)
            ->first();

        $cart_id = $cart->id ?? DB::table('cart')->insertGetId([
                'ip_address' => $_SERVER['REMOTE_ADDR'],
                'status' => 0,
            ]);

        $cart_detail = [];
        $cart_total = 0;

        if ($cart_id) {
            $cart_detail = DB::table('cart_details')
                ->where('cart_id', $cart_id)
                ->get();

            $productIds = $cart_detail->pluck('product_id');
            $products = Product::whereIn('id', $productIds)->with('avatar')->get();

            $cart_detail_res = [];
            foreach ($products as $product) {
                foreach ($cart_detail as $cd) {
                    if ($cd->product_id === $product->id) {
                        $product->quantity = $cd->quantity;
                        $cart_detail_res[$product->id] = $product;
                        break;
                    }
                }
            }

            $cart_detail = $cart_detail_res;

            foreach ($cart_detail as $product_c) {
                $cart_total += $product_c->price * $product_c->quantity;
            }
        }

        return [$cart_detail, $cart_total, $cart_id];
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public function findProductById($id)
    {
        return Product::query()
            ->with('medias')
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
        $pageSize = 10;
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

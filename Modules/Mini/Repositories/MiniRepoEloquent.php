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
    public function getLatestActiveProducts()
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
    public static function getCartData()
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
}

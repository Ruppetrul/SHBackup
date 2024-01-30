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
     * @return void
     */
    public static function getCartData() {
        $cart_id = null;

        $cart = DB::table('cart')
            ->where('ip_address', '=', $_SERVER['REMOTE_ADDR'], 'AND')
            ->where('status', '=', 0)
            ->first();

        if ($cart) {
            $cart_id = $cart->id;
        }

        $cart_detail = array();
        $cart_total = 0;

        if ($cart_id === null) {
            $cart_id = DB::table('cart')->insertGetId(
                [
                    'ip_address' => $_SERVER['REMOTE_ADDR'],
                    'status' => 0,
                ]
            );
        }

        $cart_detail = array();
        if ($cart_id) {
            $cart_detail = DB::table('cart_details')
                ->where('cart_id', '=', $cart_id, 'AND')
                ->select()->get();

            $cart_detail_res = array();

            foreach ($cart_detail as $cd) {
                $product_id = $cd->product_id;
                $product_d = Product::query()->where('id', '=', $product_id)->with('avatar')->first();
                $cart_detail_res[$product_id] = $product_d;
            }

            $cart_detail = $cart_detail_res;

            $cart_total = 0;
            foreach ($cart_detail as $product_c) {
                $cart_total +=  $product_c['price'] * $product_c['quantity'];
            }
        }

        return array($cart_detail, $cart_total, $cart_id);
    }
}

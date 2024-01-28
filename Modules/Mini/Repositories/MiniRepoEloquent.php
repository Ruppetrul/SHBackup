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

        if (Auth::check()) {
            $cart = DB::table('cart')
                ->where('user_id', '=', Auth::user()->id, 'AND')
                ->where('status', '=', 0)
                ->first();
            if ($cart) {
                $cart_id = $cart->id;
            }
        } else {
            $cart = DB::table('cart')
                ->where('ip_address', '=', $_SERVER['REMOTE_ADDR'], 'AND')
                ->where('status', '=', 0)
                ->first();

            if ($cart) {
                $cart_id = $cart->id;
            }
        }

        $cart_detail = array();
        $cart_total = 0;

        if ($cart_id === null) {
            if (Auth::check()) {
                $cart_id = DB::table('cart')->insertGetId(
                    [
                        'user_id' => Auth::user()->id,
                        'status' => 0,
                    ]
                );
            } else {
                $cart_id = DB::table('cart')->insertGetId(
                    [
                        'ip_address' => $_SERVER['REMOTE_ADDR'],
                        'status' => 0,
                    ]
                );
            }
        }

        $cart_detail = array();
        if ($cart_id) {
            $cart_detail = DB::table('cart_details')
                ->where('cart_id', '=', $cart_id, 'AND')
                ->select()->get();

            $cart_detail_res = array();

            foreach ($cart_detail as $cd) {
                $product_id = $cd->product_id;

                $product_d = Product::query()->where('id', '=', $product_id)->first();

                $cart_item = array(
                    'id'       => $product_id,
                    'title'    => $product_d->title,
                    'quantity' => $cd->count,
                    'price'    => $product_d->price,
                    'sku'      => $product_d->sku,
                    'slug'     => $product_d->slug,
                );

                if (isset($product_d->first_media)) {
                    $cart_item['first_media'] = $product_d->first_media->thumb;
                }
                $product_d->quantity = $cd->count;
                $cart_detail_res[$product_id] = $cart_item;
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

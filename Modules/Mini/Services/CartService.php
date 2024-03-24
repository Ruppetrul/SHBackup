<?php

namespace Modules\Mini\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Mini\Repositories\MiniRepoEloquent;
use Modules\Mini\Repositories\ProductRepoEloquent;

class CartService implements CartServiceInterface
{
    public static function add($productId)
    {
        list ($cart_detail, $cart_total, $cart_id) = MiniRepoEloquent::getCartData();

        $data = [
            'cart_id' => $cart_id,
            'product_id' => $productId,
            'quantity' => 1
        ];

        DB::table('cart_details')->updateOrInsert(
            [
                'cart_id' => $cart_id,
                'product_id' => $productId
            ],
            $data
        );
    }

    public static function addWithCount($productId, $quantity)
    {
        $cart_id = null;
        list ($cart_detail, $cart_total, $cart_id) = MiniRepoEloquent::getCartData();

        $prod = DB::table('cart_details')
            ->where('cart_id', '=', $cart_id)
            ->where('product_id', '=', $productId)
            ->first();

        $cart_detail_id = $prod ? $prod->id : null;

        if ($quantity > 0) {
            $data = [
                'cart_id' => $cart_id,
                'product_id' => $productId,
                'quantity' => $quantity
            ];

            if ($cart_detail_id) {
                DB::table('cart_details')->where('id', '=', $cart_detail_id)->update($data);
            } else {
                DB::table('cart_details')->insert($data);
            }
        } elseif ($cart_detail_id) {
            DB::table('cart_details')->where('id', '=', $cart_detail_id)->delete();
        }
    }


}

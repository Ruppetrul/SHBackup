<?php

namespace Modules\Mini\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Mini\Repositories\MiniRepoEloquent;
use Modules\Mini\Repositories\ProductRepoEloquent;

class CartService implements CartServiceInterface
{
    /**
     * @param $productId
     * @return void
     */
    public function add($productId, $quantity)
    {
        list ($cart_detail, $cart_total, $cart_id) = MiniRepoEloquent::getCartData();

        $cart_detail_id = DB::table('cart_details')
            ->where('cart_id', $cart_id)
            ->where('product_id', $productId)
            ->value('id');

        $data = [
            'cart_id'    => $cart_id,
            'product_id' => $productId,
            'quantity'   => $quantity
        ];

        if ($cart_detail_id) {
            DB::table('cart_details')->where('id', '=', $cart_detail_id)->update($data);
        } else {
            DB::table('cart_details')->insert($data);
        }
    }

    /**
     * @param $productId
     * @return void
     */
    public function delete($productId)
    {
        $cart_id = null;

        list ($cart_detail, $cart_total, $cart_id) = MiniRepoEloquent::getCartData();

        $prod = DB::table('cart_details')
            ->where('cart_id', '=', $cart_id, 'AND')
            ->where('product_id', '=', $productId)
            ->first();

        if ($prod->id) {
            DB::table('cart_details')->where('id', '=', $prod->id)->delete();
        }
    }
}

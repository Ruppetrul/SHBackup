<?php

namespace Modules\Mini\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Mini\Models\Product;
use Modules\Mini\Repositories\MiniRepoEloquent;
use Modules\Mini\Services\CartService;

class CartController extends Controller
{
    /**
     * Redirect route.
     *
     * @var mixed|null
     */
    private mixed $redirectRoute = null;

    public CartService $service;

    public function __construct(CartService $cartService)
    {
        $this->service = $cartService;
    }

    /**
     * Add product into session by product id & show success messag with redirect.
     *
     * @param $productId
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function add($shopId, $productId, Request $request)
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

        if ($request->ajax()) {
            list ($cart_detail, $cart_total, $cart_id) = MiniRepoEloquent::getCartData();
            return response()->json([
                'success' => true,
                'total' => $cart_total,
            ]);
        }

        return $this->successMessageWithRedirect('Add to cart successfully');
    }

    /**
     * Add product into session by product id & show success messag with redirect.
     *
     * @param $productId
     * @param $quantity
     *
     * @return \Illuminate\Http\RedirectResponse
     *@throws \Psr\Container\NotFoundExceptionInterface
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     */
    public function addWithCount($shopId, $productId, $quantity, Request $request)
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

        if ($request->ajax()) {
            list($cart_detail, $cart_total, $cart_id) = MiniRepoEloquent::getCartData();
            $line = $cart_detail[$productId] ?? null;

            return response()->json([
                'success' => (bool) $line,
                'total' => $cart_total,
                'new_line_total' => $line ? $line['price'] * $line['quantity'] : null,
            ]);
        }

        return $this->successMessageWithRedirect('Add to cart successfully');
    }

    /**
     * Delete product from session by product id.
     *
     * @param $productId
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($shopId, $productId, Request $request)
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

        $this->service->remove($productId);

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return $this->successMessageWithRedirect('Remove item from cart successfully');
    }

    /**
     * Delete all products from cart.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteAll()
    {
        $cart_id = null;
        list ($cart_detail, $cart_total, $cart_id) = MiniRepoEloquent::getCartData();
        if ($cart_id) {
            DB::table('cart_details')
                ->where('cart_id', '=', $cart_id)
                ->delete();
        }

//        $this->service->removeAll();

        $params = array('title', 'All item deleted from cart successfully');

        return $params;
    }
}

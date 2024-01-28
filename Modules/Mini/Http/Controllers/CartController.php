<?php

namespace Modules\Mini\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Mini\Models\Product;
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
        $cart_id = null;

        list ($cart_detail, $cart_total, $cart_id) = self::getCartData();

        $prod = DB::table('cart_details')
            ->where('cart_id', '=', $cart_id, 'AND')
            ->where('product_id', '=', $productId)
            ->first();
        $cart_detail_id = $prod->id ?? null;

        if ($cart_detail_id) {
            DB::table('cart_details')->where('id', '=', $cart_detail_id)->update(
                [
                    'cart_id' => $cart_id,
                    'product_id' => $productId,
                    'count' => 1
                ]
            );
        } else {
            DB::table('cart_details')->insert(
                [
                    'cart_id' => $cart_id,
                    'product_id' => $productId,
                    'count' => 1
                ]
            );
        }

        if ($request->ajax()) {
            list ($cart_detail, $cart_total, $cart_id) = self::getCartData();
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
     * @param $count
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addWithCount($shopId, $productId, $count, Request $request)
    {
        $cart_id = null;
        list ($cart_detail, $cart_total, $cart_id) = self::getCartData();

        $prod = DB::table('cart_details')
            ->where('cart_id', '=', $cart_id, 'AND')
            ->where('product_id', '=', $productId)
            ->first();

        $cart_detail_id = $prod->id ?? null;

        if ($count > 0){
            if ($cart_detail_id) {
                DB::table('cart_details')->where('id', '=', $cart_detail_id)->update(
                    [
                        'cart_id' => $cart_id,
                        'product_id' => $productId,
                        'count' => $count ?? 1
                    ]
                );
            } else {
                DB::table('cart_details')->insert(
                    [
                        'cart_id' => $cart_id,
                        'product_id' => $productId,
                        'count' => $count ?? 1
                    ]
                );
            }
        } else {
            DB::table('cart_details')->where('id', '=', $cart_detail_id)->delete();
        }

        if ($request->ajax()) {
            list ($cart_detail, $cart_total, $cart_id) = self::getCartData();
            return response()->json([
                'success' => true,
                'total' => $cart_total,
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

        list ($cart_detail, $cart_total, $cart_id) = self::getCartData();

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
        list ($cart_detail, $cart_total, $cart_id) = self::getCartData();
        if ($cart_id) {
            DB::table('cart_details')
                ->where('cart_id', '=', $cart_id)
                ->delete();
        }

//        $this->service->removeAll();

        $params = array('title', 'All item deleted from cart successfully');

        return $params;
    }

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

<?php

namespace Modules\Mini\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Modules\Mini\Repositories\MiniRepoEloquent;
use Modules\Mini\Services\CartService;
use Modules\Mini\Services\YookassaService;

class CartController extends Controller
{
    public CartService $service;

    public function __construct(CartService $cartService)
    {
        $this->service = $cartService;
    }

    public function addToCart(Request $request, $shopIdOrName, $itemId, $count = 1) {
        $this->service->add($itemId, $count);

        if ($request->ajax()) {
            list($cart_detail, $cart_total, $cart_id) = MiniRepoEloquent::getCartData();
            $line = $cart_detail[$itemId] ?? null;

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
        $this->service->delete($productId);

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return $this->successMessageWithRedirect('Remove item from cart successfully');
    }

    /**
     * @param Request $request
     */
    public function createOrder(Request $request) {
        list ($cart_detail, $cart_total, $cart_id) = MiniRepoEloquent::getCartData();

        if ($cart_total == 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cart is empty'
            ]);
        }

        $currentShopId = app('current_shop_id');

        $description = $request->get('description');
        $communication = $request->get('communication');

        $orderArray = $this->service->createOrder(
            [
                'total'         => $cart_total,
                'cart_id'       => $cart_id,
                'description'   => $description,
                'communication' => $communication,
            ],
            $cart_detail,
            $currentShopId
        );

        $shopUrl = route('mini.mini', ['shopIdOrName' => $currentShopId]);
        list ($success, $response) = YookassaService::registerOrder($orderArray, $shopUrl);

        if (!$success) {
            return response('Ошибка при регистрации ордера', 500);
        }

        $response = json_decode($response, true);

        session()->flash('success_message', 'Заказ создан успешно!');
        return redirect()->away($response['confirmation']['confirmation_url']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function getCartData(Request $request) {
        list ($cart_detail, $cart_total, $cart_id) = MiniRepoEloquent::getCartData();
        return response()->json([
            'details' => $cart_detail,
            'total'   => $cart_total,
        ]);
    }
}

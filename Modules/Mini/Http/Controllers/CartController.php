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
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function createOrder(Request $request) {
        list ($cart_detail, $cart_total, $cart_id) = MiniRepoEloquent::getCartData();
        try {
            $this->checkBeforeCreateOrder($request, $cart_total);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }

        if ($request->get('bank') == 'on') {
            list ($success, $response) = YookassaService::registerOrder($cart_id, $cart_total);

            if ($success) {
                $currentShopId = app('current_shop_id');

                $orderArray = $this->service->createOrder(
                    [
                        'total'         => $cart_total,
                        'cart_id'       => $cart_id,
                        'description'   => $request->get('description'),
                        'communication' => $request->get('communication'),
                    ],
                    $cart_detail,
                    $currentShopId
                );

                $response = json_decode($response, true);

                return redirect()->route('yookassa.payment.page', [
                    'token'        => $response['confirmation']['confirmation_token'],
                    'id'           => $response['id'],
                    'shopIdOrName' => $currentShopId,
                ]);
            }
        }
    }

    private function checkBeforeCreateOrder(Request $request, $cart_total)
    {
        if ($cart_total == 0) {
            throw new Exception('Cart is empty');
        }

        if (!$request->has('communication') || empty($request->get('communication'))) {
            throw new Exception('Communication is required');
        }
    }
}

<?php

namespace Modules\Mini\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Repositories\MiniEloquentInterface;
use Illuminate\Support\Facades\Log;
use Modules\Mini\Repositories\MiniRepoEloquentInterface;
use Modules\Mini\Repositories\MiniRepoEloquent;

class MiniController extends Controller
{
    public function mini($shopIdOrName, MiniRepoEloquentInterface $miniRepo)
    {
        $data = array_merge(
            $this->prepareBaseData($miniRepo),
        );

        return view('Mini::index', $data);
    }

    public function prepareBaseData(MiniRepoEloquentInterface $miniRepo) : array{
        list ($cart_detail, $cart_total) = $miniRepo::getCartData();
        foreach ($cart_detail as $line) {
            $line['total'] = $line['price'] * $line['quantity'];
        }

        return array(
            'shopId'      => app('current_shop_id'),
            'shopName'    => app('current_shop_name'),
            'miniRepo'    => $miniRepo,
            'cart_detail' => $cart_detail,
            'cart_total'  => $cart_total,
        );
    }

    public function carts($shopIdOrName, MiniRepoEloquentInterface $miniRepo)
    {
        return view('Mini::Pages.mini.carts.index', $this->prepareBaseData($miniRepo));
    }

    public function order($shopIdOrName, MiniRepoEloquentInterface $miniRepo)
    {
        return view('Mini::Pages.mini.order.index', $this->prepareBaseData($miniRepo));
    }

    /**
     * @param string|int $shopIdOrName
     * @param string|int $itemId
     * @param MiniRepoEloquentInterface $miniRepo
     * @return JsonResponse
     */
    public function getProduct($shopIdOrName, $itemId, MiniRepoEloquentInterface $miniRepo) {
        list ($cart_detail) = $miniRepo::getCartData();

        $product = $miniRepo->findProductById($itemId);

        if (isset($cart_detail[$product['id']])) {
            $qty = $cart_detail[$product['id']]['quantity'];
            $product['quantity_in_cart'] = $qty;
        } else {
            $product['quantity_in_cart'] = 0;
        }
        return new JsonResponse($product);
    }

    /**
     * @param string|int $shopIdOrName
     * @param MiniEloquentInterface $miniRepo
     * @param Request $request
     * @return JsonResponse
     */
    public function getActiveProducts(
        $shopIdOrName,
        MiniRepoEloquent $miniRepo,
        Request $request
    ) {
        list ($cart_detail) = $miniRepo::getCartData();

        $result = [
            'success'  => true,
            'total'    => 0,
            'view'     => null,
            'has_more' => false
        ];

        try {
            $activeProducts = $miniRepo->getActive($request);

            if ($request->get('only_data')) {
                foreach ($activeProducts as $product) {
                    if (isset($cart_detail[$product['id']])) {
                        $qty = $cart_detail[$product['id']]['quantity'];
                        $product['quantity_in_cart'] = $qty;
                    } else {
                        $product['quantity_in_cart'] = 0;
                    }

                    if (isset($product->avatar[0]->filename)) {
                        $product['avatar_url'] = asset('storage/' . $shopIdOrName . '/' . $product->avatar[0]->filename);
                    } else {
                        $product['avatar_url'] = asset('home/images/default_item_img.jpg');
                    }
                }
                $result['products'] = $activeProducts;
            } else {
                $result['view'] = view(
                    'Mini::Pages.mini.section.products',
                    [
                        'products'    => $activeProducts,
                        'cart_detail' => $cart_detail,
                        'shopId'      => app('current_shop_id'),
                        'shopName'    => app('current_shop_name'),
                    ]
                )->render();
            }
            $result['total'] = count($activeProducts);
            $result['has_more'] = ($result['total'] >= 10);
        } catch (Exception $exception) {
            Log::debug($exception->getMessage());
            $result['success'] = false;
        }

        return new JsonResponse($result);
    }
}

<?php

namespace Modules\Mini\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Mini\Repositories\MiniRepoEloquentInterface;
use Modules\Mini\Repositories\ProductRepoEloquent;
use Modules\Mini\Repositories\ProductRepoEloquentInterface;

class MiniController extends Controller
{
    public function mini($shopIdOrName, MiniRepoEloquentInterface $miniRepo)
    {
        $data = array_merge(
            $this->prepareBaseData($miniRepo),
            [
                'categories' => array(),
            ],
        );

        return view('Mini::index', $data);
    }

    public function prepareBaseData(MiniRepoEloquentInterface $miniRepo) : array{
        list ($cart_detail, $cart_total) = $miniRepo::getCartData();
        foreach ($cart_detail as $line) {
            $line['total'] = $line['price'] * $line['quantity'];
        }

        return array(
            'shopId' => app('current_shop_id'),
            'shopName' => app('current_shop_name'),
            'miniRepo' => $miniRepo,
            'cart_detail' => $cart_detail,
            'cart_total' => $cart_total,
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

    public function details($shopIdOrName, $itemId, ProductRepoEloquent $productRepoEloquent, MiniRepoEloquentInterface $miniRepo)
    {
        $data = array_merge(
            $this->prepareBaseData($miniRepo),
            ['product' => $productRepoEloquent->findProductById($itemId)],
        );

        return view('Mini::Pages.mini.details.index', $data);
    }

    /**
     * @param string|int $shopIdOrName
     * @param ProductRepoEloquentInterface $productRepoEloquent
     * @param MiniRepoEloquentInterface $miniRepo
     * @param Request $request
     * @return JsonResponse
     */
    public function getActiveProducts(
        $shopIdOrName,
        ProductRepoEloquentInterface $productRepoEloquent,
        MiniRepoEloquentInterface $miniRepo,
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
            $activeProducts = $productRepoEloquent->getActive($request);

            $result['total'] = count($activeProducts);
            $result['has_more'] = ($result['total'] >= 10);

            $result['view'] = view(
                'Mini::Pages.mini.section.products',
                [
                    'products'    => $activeProducts,
                    'cart_detail' => $cart_detail,
                    'shopId'      => app('current_shop_id'),
                    'shopName'    => app('current_shop_name'),
                ]
            )->render();
        } catch (Exception $exception) {
            $result['success'] = false;
        }

        return new JsonResponse($result);
    }
}

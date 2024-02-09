<?php

namespace Modules\Mini\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Mini\Repositories\MiniRepoEloquentInterface;
use Modules\Mini\Repositories\ProductRepoEloquent;
use Modules\Mini\Repositories\ProductRepoEloquentInterface;

class MiniController extends Controller
{
    public function mini($shopId, MiniRepoEloquentInterface $miniRepo)
    {
        $data = array_merge(
            $this->prepareBaseData($shopId, $miniRepo),
            [
//                'categories' => Category::all(),
                'categories' => array(),
                'shopId' => $shopId,
            ],
        );

        return view('Mini::index', $data);
    }

    public function prepareBaseData($shopId, MiniRepoEloquentInterface $miniRepo) : array{
        list ($cart_detail, $cart_total) = $miniRepo::getCartData();
        foreach ($cart_detail as $line) {
            $line['total'] = $line['price'] * $line['quantity'];
        }

        return array(
            'shopId' => $shopId,
            'miniRepo' => $miniRepo,
            'cart_detail' => $cart_detail,
            'cart_total' => $cart_total,
//            'cart_detail' => array(),
        );
    }

    public function carts($shopId, MiniRepoEloquentInterface $miniRepo)
    {
        return view('Mini::Pages.mini.carts.index', $this->prepareBaseData($shopId, $miniRepo));
    }
    public function order($shopId, MiniRepoEloquentInterface $miniRepo)
    {
        return view('Mini::Pages.mini.order.index', $this->prepareBaseData($shopId, $miniRepo));
    }

    public function details($shopId, $itemId, ProductRepoEloquent $productRepoEloquent, MiniRepoEloquentInterface $miniRepo)
    {
        $product = $productRepoEloquent->findProductById($itemId);

        $data = array_merge(
            $this->prepareBaseData($shopId, $miniRepo),
            ['product' => $product],
        );

        return view('Mini::Pages.mini.details.index', $data);
    }

    public function getActiveProducts($shopId, ProductRepoEloquentInterface $productRepoEloquent, MiniRepoEloquentInterface $miniRepo)
    {
        list ($cart_detail) = $miniRepo::getCartData();

        $pageSize = 10;

        $result = [
            'success'  => true,
            'total'    => 0,
            'view'     => null,
            'has_more' => false
        ];

        try {
            $activeProducts = $productRepoEloquent->getActive($pageSize);

            $result['total'] = count($activeProducts);
            if ($result['total']) {
                $result['view'] = view('Mini::Pages.mini.section.products',
                    [
                        'products' => $activeProducts,
                        'cart_detail' => $cart_detail,
                        'shopId' => $shopId
                    ])->render();
                $result['has_more'] = ($result['total'] >= $pageSize);
            }
        } catch (Exception $exception) {
            $result['success'] = false;
        }

        return new JsonResponse($result);
    }
}

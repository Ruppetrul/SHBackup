<?php

namespace Modules\Mini\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Mini\Repositories\MiniRepoEloquentInterface;
use Modules\Mini\Repositories\ProductRepoEloquent;

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
}

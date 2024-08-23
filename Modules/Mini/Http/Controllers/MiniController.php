<?php

namespace Modules\Mini\Http\Controllers;

use App\Jobs\SendEmail;
use App\Models\Order;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Repositories\MiniEloquentInterface;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Modules\Mini\Repositories\MiniRepoEloquentInterface;
use Modules\Mini\Repositories\MiniRepoEloquent;

class MiniController extends Controller
{
    public function mini(Request $request, $shopIdOrName)
    {
        if ($order = $request->get('order')) {
            list ($cart_detail, $cart_total, $cart_id) = MiniRepoEloquent::getCartData();

            DB::table('cart')
                ->where('id', $cart_id)
                ->update([
                    'status' => '1',
                    'order_id' => $order
                ]);

            session()->flash('success_message', 'Заказ создан успешно!');

            $url = $request->url();
            $query = $request->query();
            unset($query['order']);

            $newUrl = $url . '?' . http_build_query($query);

            $order = Order::find((int)$order);

            DB::setDefaultConnection('mysql');

            $instance = DB::table('shops')->where(function ($query) use ($shopIdOrName) {
                if (is_numeric($shopIdOrName)) {
                    $query->where('id', $shopIdOrName);
                } else {
                    $query->where('name', $shopIdOrName);
                }
            })->first();

            $user = DB::table('users')->where('id', '=', $instance->owner_id)->first();

            $order = $order->toArray();
            $order['lines'] = $cart_detail;

            SendEmail::dispatch($user->email, $order);

            Config::set('database.connections.shop', [
                'driver' => 'mysql',
                'host' => env('DB_HOST'),
                'database' => $instance->db_name,
                'username' => env('DB_USERNAME'),
                'password' => env('DB_PASSWORD'),
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
            ]);
            DB::setDefaultConnection('shop');

            return Redirect::to($newUrl);
        }

        return Inertia::render('Main', array_merge(
            $this->prepareBaseData(),
            [
                'message' => session('success_message')
            ]
        ));
    }

    public function detail($shopId, $itemId)
    {
        return Inertia::render('Detail', array_merge(
            $this->prepareBaseData(),
            [
                'item_id' => $itemId,
            ]
        ));
    }

    public function cart()
    {
        return Inertia::render('Cart', $this->prepareBaseData());
    }

    private function prepareBaseData() {
        return array(
            'shop_id' => app('current_shop_id'),
            'title'   => app('current_shop_name'),
        );
    }

    /**
     * @param MiniRepoEloquentInterface $miniRepo
     * @return \Inertia\Response
     */
    public function order(MiniRepoEloquentInterface $miniRepo)
    {
        list (, $cart_total) = $miniRepo::getCartData();
        return Inertia::render('Order', array_merge(
            $this->prepareBaseData(),
            [
                'total' => $cart_total
            ]
        ));
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

<?php

namespace Modules\Mini\Services;

use App\Jobs\SendEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Modules\Mini\Models\Order;
use Modules\Mini\Repositories\MiniRepoEloquent;

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

    /**
     * @param array $orderData
     */
    public function createOrder(array $orderData, $cartDetail, $currentShopId) : array
    {
        $orderArray = Order::create($orderData)->toArray();

        $orderArray['lines'] = $cartDetail;

        DB::setDefaultConnection('mysql');

        $instance = DB::table('shops')->where(function ($query) use ($currentShopId) {
            if (is_numeric($currentShopId)) {
                $query->where('id', $currentShopId);
            } else {
                $query->where('name', $currentShopId);
            }
        })->first();

        $user = DB::table('users')->where('id', '=', $instance->owner_id)->first();
        SendEmail::dispatch($user->email, $orderArray);

        if ($instance) {
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

            app()->instance('current_shop_id', $instance->id);
            app()->instance('current_shop_name', $instance->name);
        }

        return $orderArray;
    }
}

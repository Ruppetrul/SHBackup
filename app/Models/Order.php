<?php

namespace App\Models;

use App\Repositories\MiniEloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Order extends Model
{
    /**
     * @param $shop_id
     * @return array
     */
    public static function fetchOrders($shop_id): array
    {
        $orders = [];
        $success = MiniEloquent::executeWithShopConnection($shop_id, function () use (&$orders, $shop_id) {
            $ordersCollection = DB::connection('shop_connection')->table('orders')->get();

            foreach ($ordersCollection as $order) {
                $order_details = DB::connection('shop_connection')->table('cart_details')
                    ->where('cart_id', $order->cart_id)
                    ->leftJoin('products', 'products.id', '=', 'cart_details.product_id')
                    ->get()
                    ->toArray();
                $order->lines = $order_details;
                $orders[] = (array) $order;
            }

            return $orders;
        });

        return array($success, $orders);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Order extends Model
{
    public static function fetchOrders($shop_id)
    {
        $orders = [];
        $success = self::executeWithShopConnection($shop_id, function () use (&$orders, $shop_id) {
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

    private static function executeWithShopConnection($shop_id, $callback)
    {
        $shop = Shop::where('id', $shop_id)->first();
        return self::performShopQuery($shop->db_name, $callback);
    }

    private static function performShopQuery(string $db_name, $callback)
    {
        $success = true;
        try {
            DB::purge('shop_connection');
            config(['database.connections.shop_connection.database' => $db_name]);

            $callback(DB::connection('shop_connection'));
        } catch (\Exception $exception) {
            Log::error('Query error: ' . $exception->getMessage());
            $success = false;
        } finally {
            DB::disconnect('shop_connection');
        }

        return $success;
    }
}

<?php

namespace App\Repositories;

use App\Models\Shop;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MiniEloquent implements MiniEloquentInterface
{

    /**
     * @param string|int $shop_id
     * @param $callback
     * @param MiniEloquent $miniEloquent
     * @return bool
     */
    static function executeWithShopConnection($shop_id, $callback)
    {
        $shop = Shop::where('id', $shop_id)->first();
        return self::performShopQuery($shop->db_name, $callback);
    }

    /**
     * @param string $db_name
     * @param $callback
     * @return bool
     */
    static function performShopQuery(string $db_name, $callback)
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

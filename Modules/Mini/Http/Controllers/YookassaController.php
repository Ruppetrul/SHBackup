<?php

namespace Modules\Mini\Http\Controllers;

use App\Jobs\SendEmail;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Mini\Repositories\MiniRepoEloquent;

class YookassaController extends Controller
{
    public function test(Request $request) {
        Log::debug('New request from Yookassa');
        $data = $request->json()->all();

        if ($data['event'] == 'payment.succeeded') {
            $mapping = DB::table('order_mapping')->where(
                'order_yookassa_id', $data['object']['id']
            )->first();
Log::debug('Mapping');
Log::debug(json_encode(get_object_vars($mapping)));
            $currentShopId = $mapping->shop_id;
            $instance = DB::table('shops')->where(function ($query) use ($currentShopId) {
                if (is_numeric($currentShopId)) {
                    $query->where('id', $currentShopId);
                } else {
                    $query->where('name', $currentShopId);
                }
            })->first();
            $shop = Shop::query()->where('id', $mapping->shop_id)->first()->toArray();
Log::debug('$shop');
Log::debug(json_encode($shop));
            $user = User::query()->where('id', $shop['owner_id'])->first()->toArray();
Log::debug('$user');
Log::debug(json_encode($user));
            DB::table('shops');

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

            $order = DB::table('orders')->where('id', $mapping->order_id)->first();
            $order = get_object_vars($order);

            app()->instance('current_shop_id', $mapping->shop_id);

            list ($cart_detail, $cart_total, $cart_id) = MiniRepoEloquent::getCartData($order['cart_id']);

            DB::table('cart')
                ->where('id', $order['cart_id'])
                ->update([
                    'status'   => '1',
                    'order_id' => $mapping->order_id
                ]);

            $order['lines'] = $cart_detail;
            DB::setDefaultConnection('mysql');
            SendEmail::dispatch($user['email'], $order);
        }
        Log::debug(json_encode($data));
    }
}

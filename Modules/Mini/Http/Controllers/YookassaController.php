<?php

namespace Modules\Mini\Http\Controllers;

use App\Jobs\SendEmail;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Mini\Models\YookassaPayment;
use Modules\Mini\Services\CartService;

class YookassaController extends Controller
{
    public function test(Request $request) {
        Log::debug('New request from Yookassa');
        $data = $request->json()->all();
        Log::debug(json_encode($data));
    }

    /**
     * @param tring|int $shopIdOrName
     * @param string $token
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function payment($shopIdOrName, $token, Request $request) {
Log::debug('token: ' . $token);
        $payment_id = $request->get('payment_id');
        return view('Mini::payments.yookassa.process', compact('token', 'shopIdOrName', 'payment_id'));
    }

    /**
     * @param string|int $shopIdOrName
     * @param string $payment_id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function payment_end($shopIdOrName, $payment_id, Request $request) {
        sleep(1); // For reliability, so that the payment system has time to update all data and we receive the current status of the payment.

        $payment = YookassaPayment::find($payment_id);

        $payment_status = $this->checkPaymentStatus($payment->yookassa_id);

        switch ($payment_status) {
            case 'succeeded':
                $cartService = new CartService();
                $cartDump = json_decode($payment->cart_body, true);
                $orderArray = $cartService->createOrder(
                    [
                        'total'          => $cartDump['total'],
                        'cart_id'        => $cartDump['cart_id'],
                        'payment_id'     => $payment->id,
                        'description'    => $cartDump['description'],
                        'communication'  => $cartDump['communication'],
                    ],
                    $cartDump['lines']
                );

                DB::setDefaultConnection('mysql');

                $instance = DB::table('shops')->where(function ($query) use ($shopIdOrName) {
                    if (is_numeric($shopIdOrName)) {
                        $query->where('id', $shopIdOrName);
                    } else {
                        $query->where('name', $shopIdOrName);
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

                session()->flash('success_message', 'Ваш заказ успешно обработан. Сервис уже передал информацию менеджеру магазина.');
                break;
            case 'canceled':
            case 'pending':
            default:
                session()->flash('success_message', 'Произошел непредвиденный сценарий. Если с вашей карты уже списаны средства, то в ближайшее время мы обновим информацию в автоматическом режиме.');
                break;
        }

        return redirect()->route('mini.mini', [
            'shopIdOrName' => $shopIdOrName,
        ]);
    }

    /**
     * @param string|int $paymentId
     * @return string
     */
    private function checkPaymentStatus($paymentId) {
        $shopId = '356919';
        $secretKey = 'test_s4cF0XunDIGIT__KQjZEv2FkLYXpzCQVV0HYSxuT0Tc';

        $url = "https://api.yookassa.ru/v3/payments/{$paymentId}";

        $headers = array(
            'Authorization: Basic ' . base64_encode("$shopId:$secretKey"),
            'Content-Type: application/json'
        );

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            return 'Ошибка curl: ' . curl_error($ch);
        }

        curl_close($ch);

        $paymentInfo = json_decode($response, 1);
Log::debug($paymentInfo);
        $paymentStatus = $paymentInfo['status'];

        return "$paymentStatus";
    }
}

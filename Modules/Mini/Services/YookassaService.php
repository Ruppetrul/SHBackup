<?php

namespace Modules\Mini\Services;

use Illuminate\Support\Facades\Log;
use Modules\Mini\Services\CartServiceInterface;

class YookassaService
{
    public static function registerOrder($cart_id, $cart_total): array
    {
        $url = 'https://api.yookassa.ru/v3/payments';
        $shopId = '356919';
        $secretKey = 'test_s4cF0XunDIGIT__KQjZEv2FkLYXpzCQVV0HYSxuT0Tc';
        $idempotenceKey = $cart_id;

        $data = [
            'amount' => [
                'value' => $cart_total,
                'currency' => 'RUB'
            ],
            'capture' => true,
            "confirmation" => [
                "type" => "embedded"
            ],
            'description' => 'Заказ №1'
        ];

        $jsonData = json_encode($data);

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Idempotence-Key: ' . $idempotenceKey
        ]);
        curl_setopt($ch, CURLOPT_USERPWD, "$shopId:$secretKey");

        $response = curl_exec($ch);
Log::debug('payments::response: ' . $response);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        $success = false;
        if ($httpcode == 200) {
            $success = true;
        }

        return array($success, $response);
    }
}

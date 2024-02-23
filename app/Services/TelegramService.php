<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class TelegramService
{
    protected $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function addLink($shop_id)
    {
        $shopUrl = route('mini.mini', ['shopIdOrName' => $shop_id]);
        //for testing
        //$shopUrl = 'https://nautbek-custom.ru/public/api/telegram';

        $url = 'https://api.telegram.org/bot'. $this->token. '/setWebhook';

        $data = [
            'url' => $shopUrl
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $result = curl_exec($ch);
        curl_close($ch);
        Log::info(
            'Shop: ' . $shop_id . PHP_EOL
            . ' url: ' . $url . PHP_EOL
            . ' data: ' . json_encode($data) . PHP_EOL
            . ' result: ' . $result
        );
        $result = json_decode($result, true);
        return $result;
    }

    public function updateGetMe()
    {
        $url = 'https://api.telegram.org/bot'. $this->token. '/getMe';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $result = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($result, true);
        return $result;
    }
}

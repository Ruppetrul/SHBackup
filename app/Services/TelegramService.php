<?php

namespace App\Services;

use App\Models\Shop;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    /**
     * @param int $shopId
     * @param string $telegramToken
     * @return bool
     */
    public static function addTelegramToken(int $shopId, string $telegramToken): bool
    {
        if (empty($telegramToken)) {
            return false;
        }

        $result = self::sendTelegramRequest('setWebhook', [
            'url' => route('mini.mini', ['shopIdOrName' => $shopId])
        ], $telegramToken);

        if ($result['ok'] && in_array($result['description'], ['Webhook is already set', 'Webhook was set'])) {
            $shop = Shop::find($shopId);
            $shop->is_attachment_tg = 1;

            $getMeResult = self::updateGetMe($telegramToken);
            $shop->tg_name = $getMeResult['result']['username'];

            $result = self::sendTelegramRequest('setChatMenuButton', [
                'menu_button' => json_encode([
                    'type' => 'web_app',
                    'text' => 'Каталог',
                    'web_app' => [
                        'url' => url('/') . '/mini/' . $shopId
                    ],
                ])
            ], $telegramToken);

            if ($result['ok']) {
                $shop->is_attachment_tg = 1;
            }
            $shop->save();
            return true;
        }
        return false;
    }

    /**
     * @param string $telegramToken
     * @return mixed
     */
    public static function updateGetMe(string $telegramToken): mixed
    {
        return self::sendTelegramRequest('getMe', [], $telegramToken);
    }

    /**
     * @param string $method
     * @param array $data
     * @param string $telegramToken
     * @return mixed
     */
    private static function sendTelegramRequest(string $method, array $data, string $telegramToken): mixed
    {
        $url = 'https://api.telegram.org/bot'. $telegramToken . '/' . $method;

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
            'TelegramService::sendTelegramRequest method: ' . $method . PHP_EOL
            . ' url: ' . $url . PHP_EOL
            . ' data: ' . json_encode($data) . PHP_EOL
            . ' result: ' . $result
        );
        return json_decode($result, true);
    }
}

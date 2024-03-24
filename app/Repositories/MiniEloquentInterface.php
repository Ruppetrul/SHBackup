<?php

namespace App\Repositories;

interface MiniEloquentInterface
{
    static function performShopQuery(string $db_name, $callback);

    static function executeWithShopConnection($shop_id, $callback);
}

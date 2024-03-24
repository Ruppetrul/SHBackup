<?php

namespace Modules\Mini\Services;

interface CartServiceInterface
{
    public static function add($productId);

    public static function addWithCount($productId, $quantity);

    public static function remove($productId);
}

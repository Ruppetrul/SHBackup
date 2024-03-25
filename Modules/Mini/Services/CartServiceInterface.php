<?php

namespace Modules\Mini\Services;

interface CartServiceInterface
{
    public function add($productId, $quantity);

    public function delete($productId);

    public function createOrder(array $orderData, $cartDetail) : array;
}

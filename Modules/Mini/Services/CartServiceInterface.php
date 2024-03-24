<?php

namespace Modules\Mini\Services;

interface CartServiceInterface
{
    public function add($productId, $quantity);

    public function delete($productId);
}

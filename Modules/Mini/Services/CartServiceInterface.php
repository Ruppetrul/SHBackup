<?php

namespace Modules\Mini\Services;

interface CartServiceInterface
{
    public function add($productId);

    public function addWithCount($productId, $quantity);

    public function delete($productId);
}

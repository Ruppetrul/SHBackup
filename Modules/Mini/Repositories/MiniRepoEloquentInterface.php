<?php

namespace Modules\Mini\Repositories;

interface MiniRepoEloquentInterface
{
    static function getCartData();

    static function getLatestActiveProducts();

    function findProductById($id);

    function getActive($params);
}

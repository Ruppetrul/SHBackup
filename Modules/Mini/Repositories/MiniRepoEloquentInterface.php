<?php

namespace Modules\Mini\Repositories;

interface MiniRepoEloquentInterface
{
    static function getCartData();

    function getLatestActiveProducts();

    function findProductById($id);

    function getActive($params);
}

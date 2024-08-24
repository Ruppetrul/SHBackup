<?php

namespace App\Http\Controllers;

use App\Repositories\MiniEloquent;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function store(Request $request, $shop_id)
    {
        $name = $request->get('name');

        $res = MiniEloquent::executeWithShopConnection($shop_id, function ($connection) use ($name) {
            $connection->table('categories')->insert(['name' => $name]);
        });

        if (!$res) {
            return response('Error', 500);
        }

        return response('Success', 200);
    }
}

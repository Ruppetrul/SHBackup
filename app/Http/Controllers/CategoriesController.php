<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Repositories\MiniEloquent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class CategoriesController extends Controller
{
    public function store(Request $request, $shop_id)
    {
        $shop = Shop::findOrFail($shop_id);
        if (Gate::denies('categoriesStore', $shop)) abort(403);

        $name = $request->get('name');

        $res = MiniEloquent::executeWithShopConnection($shop_id, function ($connection) use ($name) {
            $connection->table('categories')->insert(['name' => $name]);
        });

        if (!$res) {
            return response(__('general.category_store_error'), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return response('Success', 200);
    }
}

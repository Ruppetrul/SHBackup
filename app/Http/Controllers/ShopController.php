<?php
namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class ShopController extends Controller {

    public function showDetails($id) {
        $shop = Shop::find($id);

        if ($shop) {
            $products = Shop::fetchProducts($shop->db_name);
            return view('shop.details', ['shop' => $shop, 'products' => $products]);
        }
        //TODO unknown shop
    }

    public function create(Request $request) {
        try {
            if (Shop::where('name', $request->name)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Shop with this name already exist.'
                ]);
            }

            $now = now();
            $shop = Shop::create([
                'name' => $request->name,
                'db_name' => 'unknown_' . $now->format('YmdHis'),
                'owner_id' => Auth::id(),
                'payment_status' => 'trial',
                'state' => 'not_created',
                'last_used_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'success' => (bool) $shop
            ]);
        } catch (\Exception $exception) {
            Log::error('ShopController error case 1: ' . $exception->getMessage());
            //TODO log exception
            return response()->json([
                'success' => false,
                'message' => 'Unknown error.'
            ]);
        }
    }

    function index() {
        $shops = Shop::where('owner_id', Auth::id())->get();
        return view('shops', ['shops' => $shops]);
    }

    function productCreate($shop_id, Request $request) {
        $title = $request->get('title');
        $price = $request->get('price');
        $data = array(
            'title' => $title,
            'price' => $price,
        );
        Shop::createProduct($shop_id, $data);

        return Redirect::route('shop.details', ['shopId' => $shop_id]);
    }

    function productDelete($shop_id, Request $request) {
        $produtId = $request->get('id');

        Shop::deleteProduct($shop_id, $produtId);

        return Redirect::route('shop.details', ['shopId' => $shop_id]);
    }

    function productUpdate(Request $request, $shopId, $itemId) {
        $title = $request->get('title');
        $price = $request->get('price');
        $data = array(
            'title' => $title,
            'price' => $price,
        );
        Shop::updateProduct($shopId, $itemId, $data);
        return Redirect::route('shop.details', ['shopId' => $shopId]);
    }

    function productEdit($shopId, $itemId = null) {
        $data = array();
        $data['shopId'] = $shopId;
        if ($itemId) {
            $data['item'] = Shop::fetchProduct($shopId, $itemId)[0];
        }

        return view('shop.product-edit', $data);
    }
}

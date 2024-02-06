<?php
namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class ShopController extends Controller {

    public function showDetails($id) {
        $shop = Shop::find($id);

        $success = false;
        if ($shop) {
            $products = [];
            if (!$shop->db_name) {
                //TODO log and report it
            } else {
                list ($success, $products) = Shop::fetchProducts($shop->id);
                if (!$success) {
                    //TODO log and report it
                }
            }
        }

//        var_dump($shop->db_name);
//        var_dump($products);
//        die();
        return view('shop.details', ['shop' => $shop, 'products' => $products, 'success' => $success]);
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
        $itemId = Shop::createProduct($shop_id, $data);

        return Redirect::route('product.edit.view', ['shopId' => $shop_id, 'itemId' => $itemId]);
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
            $data['item'] = Shop::fetchProduct($shopId, $itemId);
        }

        return view('shop.product-edit', $data);
    }

    function productUpdateImage($shopId, Request $request) {
        if (!$request->has('itemId')) {
            return response()->json(array(
                'message' => "'itemId' parameter is missing"
            ), 400);
        }

        $itemId = $request->get('itemId');

        $path = $request->file('file')->store($shopId, 'public');
        $filename = basename($path);

        if ($request->has('mediaType') && $request->get('mediaType') === 'avatar') {
            $mediaId = Shop::updateProductAvatar($shopId, $itemId, $filename, $path);
        } else {
            $mediaId = Shop::saveProductImage($shopId, $itemId, $filename);
        }

        return response()->json(array(
            'file_name' => $filename,
            'url' => asset(Storage::url($path)),
            'media_id' => $mediaId
        ));
    }

    function productDeleteMedia($shop_id, Request $request) {
        $mediaId = $request->get('media_id');
        return Shop::deleteProductMedia($shop_id, $mediaId);
    }
}

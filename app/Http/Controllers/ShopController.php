<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Shop;
use App\Services\TelegramService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ShopController extends Controller {

    public function index() {
        $shops = Shop::where('owner_id', Auth::id())->whereNotIn('state', ['deleted'])->get();
        return view('shops', ['shops' => $shops]);
    }

    public function detailsView($id) {
        $shop = Shop::where('owner_id', auth()->id())->find($id);
        list($success, $orders) = Order::fetchOrders($id);

        $success = false;
        if ($shop) {
            $products = [];
            if (!$shop->db_name) {
            } else {
                list ($success, $products) = Shop::fetchProducts($shop->id);
                if (!$success) {
                }
            }
        } else {
            return redirect()->route('shops.view');
        }

        return view('shop.details', compact('shop', 'products', 'success', 'orders'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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
                'name'           => $request->name,
                'db_name'        => 'unknown_' . $now->format('YmdHis'),
                'owner_id'       => Auth::id(),
                'payment_status' => 'trial',
                'state'          => 'not_created',
                'last_used_at'   => now(),
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);

            return response()->json([
                'success' => (bool) $shop
            ]);
        } catch (\Exception $exception) {
            Log::error('ShopController error case 1: ' . $exception->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Unknown error.'
            ]);
        }
    }

    /**
     * @param string|int $shop_id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function productCreate($shop_id, Request $request) {
        $itemId = Shop::createProduct($shop_id, [
            'title' => $request->get('title'),
            'price' => $request->get('price'),
        ]);

        return redirect()->route('product.edit.view', compact('shop_id', 'itemId'));
    }

    /**
     * @param Request $request
     * @param string|int $shopId
     * @param string|int $itemId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function productUpdate(Request $request, $shopId, $itemId) {
        Shop::updateProduct($shopId, $itemId, [
            'title' => $request->get('title'),
            'price' => $request->get('price'),
        ]);
        return redirect()->route('shop.details', ['shopIdOrName' => $shopId]);
    }

    /**
     * @param string|int $shop_id
     * @param Request $request
     * @return bool
     */
    public function productDelete($shop_id, Request $request) {
        Shop::deleteProduct($shop_id, $request->get('id'));
        return true;
    }

    /**
     * @param string|int $shopId
     * @param string|int $itemId
     */
    public function productEditView($shopId, $itemId = null) {
        $data = array();
        $data['shopId'] = $shopId;
        if ($itemId) {
            $data['item'] = Shop::fetchProduct($shopId, $itemId);
        }

        return view('shop.product-edit', $data);
    }

    /**
     * @param string|int $shopId
     * @param Request $request
     */
    public function productUpdateImage($shopId, Request $request) {
        if (!$request->has('itemId')) {
            return response()->json(array(
                'message' => "'itemId' parameter is missing"
            ), 400);
        }

        $itemId = $request->get('itemId');

        $file = $request->file('file');
        $path = $file->store($shopId, 'public');
        $filename = basename($path);

        $storagePath = storage_path('app/public/' . $path);

        $manager = new ImageManager(
            new Driver()
        );
        list($width, $height) = getimagesize($storagePath);

        $scale = $width / 1500;

        if ($width > 1500) {
            $image = $manager->read($storagePath);
            $image->resize(1500, $height / $scale);
            $image->save();
        }

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

    /**
     * @param string|int $shop_id
     * @param Request $request
     * @return bool
     */
    public function productDeleteMedia($shop_id, Request $request) {
        $mediaId = $request->get('media_id');
        return Shop::deleteProductMedia($shop_id, $mediaId);
    }

    /**
     * @param string|int $shop_id
     */
    public function shopDelete($shop_id) {
        $shop = Shop::where('owner_id', Auth::id())->where('id', $shop_id)->first();

        if (!$shop) {
            return response()->json(array(
                'message' => 'Shop does not exist'
            ), 400);
        }

        $shop->state = 'deleted';
        $updated = $shop->update();

        if ($updated) {
            return response()->json(array(
                'message' => 'Shop deleted'
            ));
        } else {
            return response()->json(array(
                'message' => 'Unknown error'
            ), 400);
        }
    }

    /**
     * @param string|int $shop_id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addTelegramToken($shop_id, Request $request) {
        $new_telegram_token = $request->get('telegram_token');

        if (empty($new_telegram_token) || !is_string($new_telegram_token)) {
            return response()->json([
                'message' => 'Telegram token is empty or is not a string'
            ], 400);
        }

        $result = TelegramService::addTelegramToken((int)$shop_id, $new_telegram_token);

        return response()->json(['result' => $result]);
    }
}

<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Shop;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Modules\Mini\Models\Category;

class ProductController extends Controller {
    /**
     * @param string|int $shop_id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store($shop_id, Request $request) {
        $itemId = Product::createProduct($shop_id, [
            'title'    => $request->get('title'),
            'price'    => $request->get('price'),
            'category' => $request->get('category'),
        ]);

        return redirect()->route('product.edit', ['shopId' => $shop_id, 'product' => $itemId]);
    }

    /**
     * @param Request $request
     * @param int $shopId
     * @param string|int $itemId
     * @return RedirectResponse
     */
    public function update(Request $request, int $shopId, $itemId): RedirectResponse
    {
        $shop = Shop::findOrFail($shopId);
        if (Gate::denies('productUpdate', $shop)) abort(403);

        Product::updateProduct($shopId, $itemId, [
            'title'    => $request->get('title'),
            'price'    => $request->get('price'),
            'category' => $request->get('category'),
        ]);
        return redirect()->route('shop.details', compact('shopId'));
    }

    /**
     * @param string|int $shop_id
     * @param Request $request
     * @return bool
     */
    public function destroy($shop_id, Request $request) {
        if (!auth()->user()->can('productDelete', Shop::findOrFail($shop_id))) {
            abort(403);
        }
        Product::deleteProduct($shop_id, $request->get('id'));
        return true;
    }

    /**
     * @param int $shopId
     * @param Product $itemId
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function create(int $shopId) {
        $shop = Shop::findOrFail($shopId);
        if (Gate::denies('productUpdate', $shop)) abort(403);

        $data = array();
        $data['shopId'] = $shopId;

        list (, $categories) = Category::fetch($shopId);
        $data['categories'] = $categories;
        return view('shop.product-edit', $data);
    }

    /**
     * @param int $shopId
     * @param Product $itemId
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function edit(int $shopId, int $itemId) {
        $shop = Shop::findOrFail($shopId);
        if (Gate::denies('productUpdate', $shop)) abort(403);

        $data = array();
        $data['shopId'] = $shopId;
        if ($itemId) {
            $data['item'] = Product::fetchProduct($shopId, $itemId);
        }

        list (, $categories) = Category::fetch($shopId);
        $data['categories'] = $categories;
        return view('shop.product-edit', $data);
    }

    /**
     * @param string|int $shopId
     * @param Request $request
     * @return JsonResponse
     */
    public function productUpdateImage($shopId, Request $request): JsonResponse
    {
        if (!$request->has('itemId')) {
            return response()->json(array(
                'message' => "'itemId' parameter is missing"
            ), 400);
        }

        $itemId = $request->get('itemId');

        $file = $request->file('file');
        try {
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
                $mediaId = Product::updateProductAvatar($shopId, $itemId, $filename, $path);
            } else {
                $mediaId = Product::saveProductImage($shopId, $itemId, $filename);
            }

            return response()->json(array(
                'success' => true,
                'data' => [
                    'file_name' => $filename,
                    'url'       => asset(Storage::url($path)),
                    'media_id'  => $mediaId ?? ''
                ]
            ));
        } catch (\Exception $e) {
            return response()->json(array(
                'success' => false,
            ));
        }
    }

    /**
     * @param string|int $shop_id
     * @param Request $request
     * @return bool
     */
    public function productDeleteMedia($shop_id, Request $request) {
        $mediaId = $request->get('media_id');
        return Product::deleteProductMedia($shop_id, $mediaId);
    }
}

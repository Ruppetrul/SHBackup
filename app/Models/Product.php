<?php

namespace App\Models;

use App\Repositories\MiniEloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Modules\Mini\Models\Media;

class Product extends Model
{
    /**
     * @param string|int $shop_id
     * @return array
     */
    public static function fetchProducts($shop_id)
    {
        $products = [];
        $success = MiniEloquent::executeWithShopConnection($shop_id, function () use (&$products, $shop_id) {
            $products = DB::connection('shop_connection')->table('products')->get()->map(function ($item) use ($shop_id) {
                $product = (array) $item;

                if ($product['first_media_id']) {
                    $media = DB::connection('shop_connection')->table('medias')->where('id', $product['first_media_id'])->first();
                    if ($media) {
                        $product['avatar_url'] = asset(Storage::url('/')) . '/' . $shop_id . '/' . $media->filename; ;
                    }
                }

                return $product;
            })->all();
        });

        return array($success, $products);
    }

    /**
     * @param string|int $shop_id
     * @param $data
     * @return mixed|null
     */
    public static function createProduct($shop_id, $data)
    {
        $itemId = null;
        MiniEloquent::executeWithShopConnection($shop_id, function ($connection) use ($data, &$itemId) {
            DB::setDefaultConnection('shop_connection');

            try {
                $prd = new \Modules\Mini\Models\Product();
                $prd->fill($data);
                $prd->save();

                $prd->categories()->sync($data['category'] == 0 ? [] : [$data['category']]);
                } catch (\Exception $exception) {
                    dd($exception->getMessage());
                }
            $itemId = $prd->id;
        });

        return $itemId;
    }

    /**
     * @param string|int $shop_id
     * @param string|int $product_id
     * @param $data
     * @return void
     */
    public static function updateProduct($shop_id, $product_id, $data)
    {
        MiniEloquent::executeWithShopConnection($shop_id, function ($connection) use ($product_id, $data) {
            DB::setDefaultConnection('shop_connection');

            $prd = \Modules\Mini\Models\Product::find($product_id);
            $prd->fill($data);
            $prd->save();
            $prd->categories()->sync($data['category'] == 0 ? [] : [$data['category']]);
        });
    }

    /**
     * @param string|int $shop_id
     * @param string|int $product_id
     * @return void
     */
    public static function deleteProduct($shop_id, $product_id)
    {
        MiniEloquent::executeWithShopConnection($shop_id, function ($connection) use ($product_id) {
            $connection->table('products')->where('id', $product_id)->delete();
        });
    }

    /**
     * @param string|int $shop_id
     * @param string|int $item_id
     * @return array|null
     */
    public static function fetchProduct($shop_id, $item_id)
    {
        $item = null;
        MiniEloquent::executeWithShopConnection($shop_id, function ($connection) use ($shop_id, $item_id, &$item) {
            DB::setDefaultConnection('shop_connection');

            $product = \Modules\Mini\Models\Product::where('id', $item_id)
                ->with('medias')
                ->firstOrFail();

            // for sirst time
            $product->category = $product->categories()->first()->id ?? null;

            $medias = Media::where('item_id', $item_id)
                ->where('id', '!=', $product->first_media_id)
                ->get();

            $medias = $medias->map(function ($media) use ($shop_id) {
                $filename = $media->filename;
                $media->url = asset(Storage::url('/')) . '/' . $shop_id . '/' . $filename;
                return $media;
            });

            $product->medias = $medias;

            $item = $product->toArray();
            $item['medias'] = $medias;
        });
        DB::setDefaultConnection('mysql');
        return $item;
    }

    /**
     * @param string|int $shop_id
     * @param string|int $item_id
     * @param $media_url
     * @param $path
     * @return mixed|null
     */
    public static function updateProductAvatar($shop_id, $item_id, $media_url, $path) {
        $mediaId = null;
        MiniEloquent::executeWithShopConnection($shop_id, function ($connection) use ($item_id, $media_url, $shop_id, $path, &$mediaId) {
            $avatarMediaId = $connection->table('products')->where('id', $item_id)->value('first_media_id');
            Log::debug($avatarMediaId);
            if ($avatarMediaId) {
                $fileName = $connection->table('medias')->where('id', $avatarMediaId)->value('filename');
                if ($fileName) {
                    Storage::delete('public/' . $shop_id . '/' . $fileName);
                }
                $connection->table('medias')->where('id', $avatarMediaId)->delete();
            }

            $mediaId = $connection->table('medias')->insertGetId([
                'item_id' => $item_id,
                'filename' => $media_url
            ]);

            $connection->table('products')->where('id', $item_id)->update(['first_media_id' => $mediaId]);
        });
        return $mediaId;
    }

    /**
     * @param string|int $shop_id
     * @param string|int $item_id
     * @param $media_url
     * @return mixed|null
     */
    public static function saveProductImage($shop_id, $item_id, $media_url) {
        $mediaId = null;
        MiniEloquent::executeWithShopConnection($shop_id, function ($connection) use ($item_id, $media_url, &$mediaId) {
            $mediaId = $connection->table('medias')->insertGetId([
                'item_id' => $item_id,
                'filename' => $media_url
            ]);
        });
        return $mediaId;
    }

    /**
     * @param string|int $shop_id
     * @param mixed $mediaId
     * @return bool
     */
    public static function deleteProductMedia($shop_id, mixed $mediaId)
    {
        return MiniEloquent::executeWithShopConnection($shop_id, function ($connection) use ($mediaId) {
            $connection->table('medias')->where('id', $mediaId)->delete();
            $connection->table('products')->where('first_media_id', $mediaId)->update(['first_media_id' => null]);
        });
    }
}

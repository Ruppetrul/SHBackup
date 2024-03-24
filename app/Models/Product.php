<?php

namespace App\Models;

use App\Repositories\MiniEloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
            $itemId = $connection->table('products')->insertGetId($data);
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
            $connection->table('products')->where('id', $product_id)->update($data);
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
            $item = (array) $connection
                ->table('products')
                ->select('products.*', 'medias.filename as avatar')
                ->where('products.id', $item_id)
                ->leftJoin('medias', 'products.first_media_id', '=', 'medias.id')
                ->first();

            $v = $connection
                ->table('medias')
                ->select('medias.*')
                ->where('medias.item_id', $item_id);

            if (!empty($item['first_media_id'])) {
                $v->whereNotIn('medias.id', [$item['first_media_id']]);
            }

            $v = $v->get();

            $v = $v->map(function ($media) use ($shop_id) {
                $filename = $media->filename;
                $media->url = asset(Storage::url('/')) . '/' . $shop_id . '/' . $filename;
                return $media;
            });
            $item['medias'] = $v;
        });
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

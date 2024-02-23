<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class Shop extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'db_name',
        'owner_id',
        'payment_status',
        'state',
        'last_used_at',
    ];

    public static function create(array $attributes = [])
    {
        $instance = new static;
        $instance->fill($attributes)->save();

        if ($instance) {
            $db_name = self::copyDatabase($instance->id);
            $instance->db_name = $db_name;
            $instance->state = 'created';
            $instance->save();
        }

        return $instance;
    }

    public static function copyDatabase(int $id)
    {
        $destinationDatabase = 'shop_' . $id;

        DB::statement("CREATE DATABASE $destinationDatabase;");

        self::performShopQuery($destinationDatabase,  function () {
            $dumpPath = Storage::path('dump/' . env('DB_DATABASE_DEFAULT') . '.sql');
            $dumpPath = str_replace('/', '\\', $dumpPath);

            if (!file_exists($dumpPath)) {
                //TODO handle
            }

            DB::connection('shop_connection')->unprepared(file_get_contents($dumpPath));
        });

        return $destinationDatabase;
    }

    public static function fetchProducts($shop_id)
    {
        $products = [];
        $success = self::executeWithShopConnection($shop_id, function () use (&$products, $shop_id) {
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

    public static function createProduct($shop_id, $data)
    {
        $itemId = null;
        self::executeWithShopConnection($shop_id, function ($connection) use ($data, &$itemId) {
            $itemId = $connection->table('products')->insertGetId($data);
        });

        return $itemId;
    }

    public static function updateProduct($shop_id, $product_id, $data)
    {
        self::executeWithShopConnection($shop_id, function ($connection) use ($product_id, $data) {
            $connection->table('products')->where('id', $product_id)->update($data);
        });
    }

    public static function deleteProduct($shop_id, $product_id)
    {
        self::executeWithShopConnection($shop_id, function ($connection) use ($product_id) {
            $connection->table('products')->where('id', $product_id)->delete();
        });
    }

    private static function executeWithShopConnection($shop_id, $callback)
    {
        $shop = Shop::where('id', $shop_id)->first();
        return self::performShopQuery($shop->db_name, $callback);
    }

    private static function performShopQuery(string $db_name, $callback)
    {
        $success = true;
        try {
            DB::purge('shop_connection');
            config(['database.connections.shop_connection.database' => $db_name]);

            $callback(DB::connection('shop_connection'));
        } catch (\Exception $exception) {
            Log::error('Query error: ' . $exception->getMessage());
            $success = false;
            //TODO report exception
        } finally {
            DB::disconnect('shop_connection');
        }

        return $success;
    }

    public static function fetchProduct($shop_id, $item_id)
    {
        $item = null;
        self::executeWithShopConnection($shop_id, function ($connection) use ($shop_id, $item_id, &$item) {
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

            // Перебор и мутация элементов
            $v = $v->map(function ($media) use ($shop_id) {
                $filename = $media->filename;
                $media->url = asset(Storage::url('/')) . '/' . $shop_id . '/' . $filename;
//                var_dump($media);
//                die();
//                // Здесь вы можете изменить одно из значений
//                $media->exampleField = 'новое значение'; // Пример мутации

                return $media;
            });

//            var_dump($v);
//            die();

//            var_dump($v);
//            var_dump($item['first_media_id']);
//            var_dump($item_id);
//            die();
            $item['medias'] = $v;
        });
        return $item;
    }

    public static function updateProductAvatar($shop_id, $item_id, $media_url, $path) {
        $mediaId = null;
        self::executeWithShopConnection($shop_id, function ($connection) use ($item_id, $media_url, $shop_id, $path, &$mediaId) {
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

    public static function saveProductImage($shop_id, $item_id, $media_url) {
        $mediaId = null;
        self::executeWithShopConnection($shop_id, function ($connection) use ($item_id, $media_url, &$mediaId) {
            $mediaId = $connection->table('medias')->insertGetId([
                'item_id' => $item_id,
                'filename' => $media_url
            ]);
        });
        return $mediaId;
    }

    public static function deleteProductMedia($shop_id, mixed $mediaId)
    {
        return self::executeWithShopConnection($shop_id, function ($connection) use ($mediaId) {
            $connection->table('medias')->where('id', $mediaId)->delete();
            $connection->table('products')->where('first_media_id', $mediaId)->update(['first_media_id' => null]);
        });
    }
}

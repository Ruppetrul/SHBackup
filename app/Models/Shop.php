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

        $sql = "CREATE DATABASE $destinationDatabase;";

        DB::statement($sql);

        DB::purge('shop_connection');
        $config = config('database.connections.shop_connection');
        $config['database'] = $destinationDatabase;
        config(['database.connections.shop_connection' => $config]);

        $dumpPath = Storage::path('dump/default_db.sql');

        $dumpPath = str_replace('/', '\\', $dumpPath);

        if (!file_exists($dumpPath)) {
            //TODO handle
        }

        DB::connection('shop_connection')->unprepared(file_get_contents($dumpPath));
        DB::disconnect('shop_connection');

        return $destinationDatabase;
    }

    public static function fetchProducts($shop_id)
    {
        $products = [];
        $success = self::executeShopAction($shop_id, function () use (&$products) {
            $products = DB::connection('shop_connection')->table('products')->get()->map(function ($item) {
                return (array) $item;
            })->all();
        }, 'Shop error case 4');

        return array($success, $products);
    }

    public static function createProduct($shop_id, $data)
    {
        return self::executeShopAction($shop_id, function ($connection) use ($data) {
            return $connection->table('products')->insertGetId($data);
        }, 'Shop error case 1');
    }

    public static function updateProduct($shop_id, $product_id, $data)
    {
        self::executeShopAction($shop_id, function ($connection) use ($product_id, $data) {
            $connection->table('products')->where('id', $product_id)->update($data);
        }, 'Shop error case 3');
    }

    public static function deleteProduct($shop_id, $product_id)
    {
        self::executeShopAction($shop_id, function ($connection) use ($product_id) {
            $connection->table('products')->where('id', $product_id)->delete();
        }, 'Shop error case 2');
    }

    private static function executeShopAction($shop_id, $callback, $error_message_prefix)
    {
        $shop = Shop::where('id', $shop_id)->first();
        $dbName = $shop->db_name;

        DB::purge('shop_connection');
        $config = config('database.connections.shop_connection');
        $config['database'] = $dbName;
        config(['database.connections.shop_connection' => $config]);

        $success = true;
        try {
            $callback(DB::connection('shop_connection'));
        } catch (\Exception $exception) {
            Log::error($error_message_prefix . ': ' . $exception->getMessage());
            $success = false;
            //TODO report exception
        }

        DB::disconnect('shop_connection');
        return $success;
    }

    public static function fetchProduct($shop_id, $item_id)
    {
        $shop = Shop::where('id', $shop_id)->first();
        $dbName = $shop->db_name;

        DB::purge('shop_connection');
        $config = config('database.connections.shop_connection');
        $config['database'] = $dbName;
        config(['database.connections.shop_connection' => $config]);

        $product = DB::connection('shop_connection')
            ->table('products')
            ->select('products.*', 'medias.filename as avatar')
            ->where('products.id', $item_id)
            ->leftJoin('medias', 'products.first_media_id', '=', 'medias.id')
            ->first();
        DB::disconnect('shop_connection');

        return (array) $product;
    }

    public static function updateProductAvatar($shop_id, $item_id, $media_url, $path) {
        self::executeShopAction($shop_id, function ($connection) use ($item_id, $media_url, $shop_id, $path) {
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
        }, 'Shop error case 4');
    }
}

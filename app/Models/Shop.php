<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    public static function fetchProducts($db_name)
    {
        DB::purge('shop_connection');
        $config = config('database.connections.shop_connection');
        $config['database'] = $db_name;
        config(['database.connections.shop_connection' => $config]);
        $products = DB::connection('shop_connection')->table('products')->get()->map(function ($item) {
            return (array) $item;
        })->all();
        DB::disconnect('shop_connection');

        return $products;
    }
}

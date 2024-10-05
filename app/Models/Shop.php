<?php

namespace App\Models;

use App\Repositories\MiniEloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * @method static where(string $string, string $string1)
 * @method static findOrFail(int $shopId)
 * @property int $id
 */
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

    /**
     * @param array $attributes
     * @return static
     */
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

    /**
     * @param int $id
     * @return string
     */
    public static function copyDatabase(int $id)
    {
        $destinationDatabase = 'shop_' . $id;

        DB::statement("CREATE DATABASE $destinationDatabase;");

        MiniEloquent::performShopQuery($destinationDatabase,  function () {
            $dumpPath = Storage::path('dump/' . env('DB_DATABASE_DEFAULT') . '.sql');

            if (!file_exists($dumpPath)) {
                //TODO handle
            }

            DB::connection('shop_connection')->unprepared(file_get_contents($dumpPath));
        });

        return $destinationDatabase;
    }
}

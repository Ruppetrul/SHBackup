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
        $sourceDatabase = 'default_db';

        $destinationDatabase = 'shop_' . $id;

        $sql = "CREATE DATABASE $destinationDatabase;";

        DB::statement($sql);

        $tables = DB::select("SHOW TABLES FROM $sourceDatabase");

        foreach ($tables as $table) {
            $tableName = reset($table);

            DB::statement("CREATE TABLE $destinationDatabase.$tableName AS SELECT * FROM $sourceDatabase.$tableName");
        }

        return $destinationDatabase;
    }
}

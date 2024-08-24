<?php

namespace Modules\Mini\Models;

use App\Repositories\MiniEloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Article\Models\Article;
use Modules\Category\Database\Factories\CategoryFactory;
use Modules\Category\Enums\CategoryStatusEnum;
use Modules\Media\Models\Media;
use Modules\Product\Models\Product;
use Modules\User\Models\User;

/**
 * @property $status
 */
class Category extends Model
{
    use HasFactory;

    /**
     * Set fillable for columns.
     *
     * @var string[]
     */
    protected $fillable = ['name'];

    protected $table = 'categories';

    public static function fetch($shop_id)
    {
        $categories = [];
        $success = MiniEloquent::executeWithShopConnection($shop_id, function ($connection) use (&$categories, $shop_id) {
            $categories = $connection->table('categories')->get()->all();
        });

        return array($success, $categories);
    }
}

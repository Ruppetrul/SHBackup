<?php

namespace Modules\Mini\Models;

use App\Models\Shop;
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
}

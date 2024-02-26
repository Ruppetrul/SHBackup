<?php

namespace Modules\Mini\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Mini\Models\Enums\ProductStatusEnum;

class Order extends Model {

    use HasFactory;
    protected $fillable = [
        'total',
        'cart_id',
    ];
}

<?php

namespace Modules\Mini\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Mini\Models\Enums\ProductStatusEnum;

class YookassaPayment extends Model {

    use HasFactory;

    protected $fillable = [
        'id',
        'yookassa_id',
        'body',
        'cart_doby',
        'cart_id',
    ];
}

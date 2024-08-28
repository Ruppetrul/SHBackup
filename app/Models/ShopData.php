<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopData extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'address',
        'fio',
        'inn',
        'ogrnip',
    ];
}

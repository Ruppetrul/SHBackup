<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments_credentials';
    const TAG_YOOKASSA = 'yookassa';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type',
        'data',
    ];

    public static function store(int $shopId, string $type, string $data) {
        $model = Payment::where('shop_id', $shopId)->where('type', self::TAG_YOOKASSA)->first();

        if (!$model) {
            $model = new Payment();
        }
        $model->shop_id = $shopId;
        $model->type = $type;
        $model->data = $data;
        return $model->save();
    }

    public static function find($id, string $TAG_YOOKASSA)
    {
        return Payment::where('shop_id', $id)
            ->where('type', $TAG_YOOKASSA)
            ->value('data');
    }
}

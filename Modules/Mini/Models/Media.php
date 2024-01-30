<?php

namespace Modules\Mini\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Media\Services\MediaFileService;

class Media extends Model
{
    protected $table = 'medias';

    protected $casts = ['files' => 'json'];

    /**
     * Fillable columns.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id', 'filename', 'files',
        'type', 'is_private',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

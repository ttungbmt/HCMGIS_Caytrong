<?php

namespace App\Models;

use Rennokki\QueryCache\Traits\QueryCacheable;

class LoaiGh extends Model
{
    use QueryCacheable;

    public $cacheFor = 30*24*60*60;

    protected static $flushCacheOnUpdate = true;

    protected $table = 'dm_loai_gh';

    protected $fillable = ['nhom_gh_id', 'ten'];

    public function nhom_gh()
    {
        return $this->belongsTo(NhomGh::class, 'nhom_gh_id');
    }
}

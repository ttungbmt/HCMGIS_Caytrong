<?php

namespace App\Models;

use Rennokki\QueryCache\Traits\QueryCacheable;

class NhomGh extends Model
{
    use QueryCacheable;

    public $cacheFor = 30*24*60*60;

    protected static $flushCacheOnUpdate = true;

    protected $table = 'dm_nhom_gh';

    protected $fillable = ['ten'];
}

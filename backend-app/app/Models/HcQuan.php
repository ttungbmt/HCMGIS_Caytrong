<?php

namespace App\Models;

use Rennokki\QueryCache\Traits\QueryCacheable;

class HcQuan extends \Larabase\Models\HcQuan
{
    use QueryCacheable;

    public $cacheFor = 30*24*60*60;

    protected static $flushCacheOnUpdate = true;

    protected $fillable = ['maquan', 'tenquan'];

    public $timestamps = false;
}

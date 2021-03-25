<?php
namespace App\Models;

use Rennokki\QueryCache\Traits\QueryCacheable;

class HcPhuong extends \Larabase\Models\HcPhuong
{
    use QueryCacheable;

    public $cacheFor = 30*24*60*60;

    protected static $flushCacheOnUpdate = true;

    public $fillable = ['maquan', 'tenquan', 'maphuong', 'tenphuong',];

    public $timestamps = false;

    public function thuadats(){
        return $this->hasMany(Ranhthua::class, 'maphuong', 'maphuong');
    }
}





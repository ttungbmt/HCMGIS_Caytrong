<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Rennokki\QueryCache\Traits\QueryCacheable;

class Caytrong extends Model
{
    use QueryCacheable;

    public $cacheFor = 30*24*60*60;

    protected static $flushCacheOnUpdate = true;

    protected $table = 'dm_caytrong';

    protected $fillable = ['loai_ctr', 'created_at', 'updated_at'];


}

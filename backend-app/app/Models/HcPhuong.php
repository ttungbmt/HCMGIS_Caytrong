<?php
namespace App\Models;

class HcPhuong extends \Larabase\Models\HcPhuong
{
    public $fillable = ['maquan', 'tenquan', 'maphuong', 'tenphuong',];

    public $timestamps = false;

    public function thuadats(){
        return $this->hasMany(Ranhthua::class, 'maphuong', 'maphuong');
    }
}





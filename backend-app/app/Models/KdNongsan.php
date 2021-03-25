<?php
namespace App\Models;


class KdNongsan extends GeoModel
{
    protected $table = 'kd_nongsan';

    protected $fillable = ['ten', 'diachi', 'maquan', 'maphuong'];

    public function quan()
    {
        return $this->belongsTo(HcQuan::class, 'maquan', 'maquan');
    }

    public function phuong(){
        return $this->belongsTo(HcPhuong::class, 'maphuong', 'maphuong');
    }
}




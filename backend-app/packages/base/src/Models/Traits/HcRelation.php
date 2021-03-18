<?php

namespace Larabase\Models\Traits;

use Larabase\Models\HcPhuong;
use Larabase\Models\HcQuan;

trait HcRelation
{
    public function quan()
    {
        return $this->belongsTo(HcQuan::class, 'maquan', 'maquan');
    }

    public function phuong()
    {
        return $this->belongsTo(HcPhuong::class, 'maphuong', 'maphuong');
    }
}

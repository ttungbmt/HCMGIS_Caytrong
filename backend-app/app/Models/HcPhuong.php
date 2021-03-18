<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HcPhuong extends Model
{
    use HasFactory;

    protected $table = 'hc_phuong';

    public $fillable = ['maquan', 'tenquan', 'maphuong', 'tenphuong',];

    public $timestamps = false;

    public function quan()
    {
        return $this->belongsTo(HcQuan::class, 'maquan', 'maquan');
    }

    public function thuadats(){
        return $this->hasMany(Ranhthua::class, 'maphuong', 'maphuong');
    }
}





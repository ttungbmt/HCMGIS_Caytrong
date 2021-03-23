<?php

namespace App\Models;

class Dichte extends Model
{
    protected $table = 'dichte';

    protected $fillable = ['nongho_id', 'loai_gh_id', 'thuoc_bvtv', 'loai_ctr_id', 'solan_vu', 'hieuqua_sdt',];

    public $timestamps = false;

    public function nongho(){
        return $this->belongsTo(Nongho::class, 'nongho_id');
    }

    public function caytrong(){
        return $this->belongsTo(Caytrong::class, 'loai_ctr_id');
    }

    public function loai_gh(){
        return $this->belongsTo(LoaiGh::class, 'loai_gh_id');
    }

    public function loai_ctr(){
        return $this->belongsTo(Caytrong::class, 'loai_ctr_id');
    }

    public function nhom_gh()
    {
        return $this->hasOneThrough( NhomGh::class,  LoaiGh::class, 'id', 'id', 'id', 'id');
    }
}







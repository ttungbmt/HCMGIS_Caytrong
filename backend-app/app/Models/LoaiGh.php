<?php

namespace App\Models;

class LoaiGh extends Model
{
    protected $table = 'dm_loai_gh';

    protected $fillable = ['nhom_gh_id', 'ten'];

    public function nhom_gh()
    {
        return $this->belongsTo(NhomGh::class, 'nhom_gh_id');
    }
}

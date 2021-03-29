<?php

namespace App\Models;


class NonghoRt extends Model
{
    protected $table = 'nongho_rt';

    protected $fillable = ['nongho_id', 'ranhthua_id', 'dt', 'diachi', 'to_dp', 'khupho_ap'];

    public $timestamps = false;
}



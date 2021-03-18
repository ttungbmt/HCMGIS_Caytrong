<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DmLoaidat extends Model
{
    use HasFactory;

    protected $table = 'dm_loaidat';

    protected $fillable = ['st', 'loaidat', 'madat', 'somau', 'fillColor',];

    public $timestamps = false;


}

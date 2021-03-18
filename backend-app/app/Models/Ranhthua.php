<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ranhthua extends Model
{
    use HasFactory;

    protected $table = 'pg_ranhthua';

    protected $fillable = [];

    public $timestamps = false;

    public function phuong()
    {
        return $this->belongsTo(HcPhuong::class, 'maphuong', 'maphuong');
    }
}

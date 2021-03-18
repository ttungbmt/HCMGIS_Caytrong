<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HcQuan extends Model
{
    use HasFactory;

    protected $table = 'hc_quan';

    protected $fillable = ['maquan', 'tenquan'];

    public $timestamps = false;

    public function phuongs()
    {
        return $this->hasMany(HcPhuong::class, 'maquan', 'maquan');
    }
}

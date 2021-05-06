<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Baocao extends Model
{
    use HasFactory;

    protected $casts = [
        'data' => 'collection'
    ];
}

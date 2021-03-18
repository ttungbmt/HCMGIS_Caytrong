<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Caytrong extends Model
{
    protected $table = 'dm_caytrong';

    protected $fillable = ['loai_ctr', 'created_at', 'updated_at'];
}

<?php
namespace App\Models;


class DientichSx extends Model
{
    protected $table = 'dientich_sx';

    protected $fillable = ['nongho_id', 'loai_ctr_id', 'dt_gt', 'dt_vg', 'ma_cn', 'sovu_ct', 'nangsuat_bq',];

    public $timestamps = false;

    public function nongho(){
        return $this->belongsTo(Nongho::class, 'nongho_id');
    }

    public function caytrong(){
        return $this->belongsTo(Caytrong::class, 'loai_ctr_id');
    }
}








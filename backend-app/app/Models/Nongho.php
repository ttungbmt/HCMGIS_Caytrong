<?php
namespace App\Models;

class Nongho extends Model
{
    protected $table = 'nongho';

    protected $fillable = ['hoten', 'diachi', 'ngaydieutra', 'maquan', 'maphuong', 'ghichu'];

    protected $dates = ['ngaydieutra'];

    public function quan()
    {
        return $this->belongsTo(HcQuan::class, 'maquan', 'maquan');
    }

    public function phuong(){
        return $this->belongsTo(HcPhuong::class, 'maphuong', 'maphuong');
    }

    public function thuadats(){
        return $this->belongsToMany(Ranhthua::class, 'nongho_rt')->withPivot('dt');
    }

    public function dientichs(){
        return $this->hasMany(DientichSx::class, 'nongho_id');
    }

    public function dichtes(){
        return $this->hasMany(Dichte::class, 'nongho_id');
    }
}

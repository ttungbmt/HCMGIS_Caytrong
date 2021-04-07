<?php
namespace App\Nova\Filters;

use App\Models\HcPhuong;
use AwesomeNova\Filters\DependentFilter;
use Illuminate\Http\Request;

class PhuongFilter extends DependentFilter
{
    public $dependentOf = ['maquan'];

    public function name()
    {
        return __('app.phuong');
    }

    public $attribute = 'maphuong';

    public function options(Request $request, array $filters = [])
    {
        $maquan = data_get($filters, 'maquan');
        return HcPhuong::when($maquan, fn($q) => $q->where('maquan', $maquan))->get()->sortBy('tenphuong', SORT_NATURAL)->pluck('tenphuong', 'maphuong');
    }
}

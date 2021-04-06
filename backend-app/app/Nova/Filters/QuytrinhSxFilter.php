<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class QuytrinhSxFilter extends Filter
{
    public function name()
    {
        return __('app.quytrinh_sx');
    }

    public function apply(Request $request, $query, $value)
    {
        if($value === 'th') return $query->whereHas('dientichs', fn ($query) => $query->where('dt_gt', '>', 0));
        elseif ($value === 'vg') $query->whereHas('dientichs', fn ($query) => $query->where('dt_vg', '>', 0));

        return $query;
    }

    public function options(Request $request)
    {
        return array_flip([
            'th' => 'Truyền thống',
            'vg' => 'VietGAP',
        ]);
    }

}

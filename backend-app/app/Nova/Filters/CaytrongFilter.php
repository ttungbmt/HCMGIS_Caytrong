<?php
namespace App\Nova\Filters;

use App\Models\Caytrong;
use Illuminate\Http\Request;
use OptimistDigtal\NovaMultiselectFilter\MultiselectFilter;

class CaytrongFilter extends MultiselectFilter
{

    public function name()
    {
        return __('app.caytrong');
    }

    public function apply(Request $request, $query, $value)
    {
        return $query->whereIn('loai_ctr_id', $value);
    }

    public function options(Request $request)
    {
        return Caytrong::pluck('ten','id');
    }
}

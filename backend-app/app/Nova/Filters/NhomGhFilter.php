<?php
namespace App\Nova\Filters;

use App\Models\NhomGh;
use Illuminate\Http\Request;
use OptimistDigtal\NovaMultiselectFilter\MultiselectFilter;

class NhomGhFilter extends MultiselectFilter
{

    public function name()
    {
        return __('app.nhom_gh');
    }

    public function apply(Request $request, $query, $value)
    {
        return $query->whereIn('nhom_gh_id', $value);
    }

    public function options(Request $request)
    {
        return NhomGh::pluck('ten', 'id');
    }
}

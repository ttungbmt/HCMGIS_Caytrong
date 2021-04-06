<?php

namespace App\Nova\Filters;

use App\Models\HcQuan;
use AwesomeNova\Filters\DependentFilter;
use Illuminate\Http\Request;

class QuanFilter extends DependentFilter
{
    public function name()
    {
        return __('app.quan');
    }

    public $attribute = 'maquan';

    public function options(Request $request, array $filters = [])
    {
        return HcQuan::pluck('tenquan', 'maquan');
    }
}

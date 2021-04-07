<?php

namespace App\Nova\Metrics;

use App\Models\DientichSx;
use App\Models\Nongho;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;

class QuytrinhSxCount extends Partition
{
    public function name()
    {
        return __('app.quytrinh_sx');
    }
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        $q0 = DientichSx::selectRaw(collect([
            'nongho_id',
            'CASE WHEN MAX(dt_gt) > 0 THEN 1 ELSE 0 END dt_gt',
            'CASE WHEN MAX(dt_vg) > 0 THEN 1 ELSE 0 END dt_vg',
        ])->implode(', '))->groupBy('nongho_id');
        $q1 = DB::table(DB::raw("({$q0->toSql()}) dt"))->selectRaw("SUM(dt_gt) th, SUM(dt_vg) vg");

        $qtsx = ['th' => 'Truyền thống', 'vg' => 'VietGAP'];

        return $this->result((array)$q1->first())
            ->label(fn ($key) => data_get($qtsx, $key))
            ->colors([
                'th' => '#88C047',
                'vg' => '#F5573B',
            ]);
    }

    /**
     * Determine for how many minutes the metric should be cached.
     *
     * @return  \DateTimeInterface|\DateInterval|float|int
     */
    public function cacheFor()
    {
//         return now()->addMinutes(5);
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'quytrinh-sx-count';
    }
}

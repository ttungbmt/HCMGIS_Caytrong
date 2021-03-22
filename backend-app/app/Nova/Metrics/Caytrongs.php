<?php

namespace App\Nova\Metrics;

use App\Models\Caytrong;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;

class Caytrongs extends Partition
{
    public function name()
    {
        return 'Số hộ trồng cây';
    }

    /**
     * Calculate the value of the metric.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        $ctrs = Caytrong::pluck('ten', 'id');

        $result = DB::table('dientich_sx as dt')
            ->select(DB::raw('loai_ctr_id, count(*)'))
            ->groupBy('dt.loai_ctr_id')
            ->get()->mapWithKeys(fn($i) => [data_get($ctrs, $i->loai_ctr_id) => $i->count])->all();

        return $this->result($result);
    }

    /**
     * Determine for how many minutes the metric should be cached.
     *
     * @return  \DateTimeInterface|\DateInterval|float|int
     */
    public function cacheFor()
    {
        // return now()->addMinutes(5);
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'caytrongs';
    }
}

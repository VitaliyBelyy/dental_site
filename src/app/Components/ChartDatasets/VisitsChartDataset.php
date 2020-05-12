<?php

namespace App\Components\ChartDatasets;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Visit;

class VisitsChartDataset extends AbstractChartDataset
{
    protected function getQuery(): Builder
    {
        return Visit::query()
            ->select([
                DB::raw('COUNT(*) AS chart_value'),
                DB::raw('MAX(`date`) AS max_date'),
            ])
            ->orderBy('max_date');
    }
}

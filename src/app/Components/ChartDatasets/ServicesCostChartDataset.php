<?php

namespace App\Components\ChartDatasets;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Visit;

class ServicesCostChartDataset extends AbstractChartDataset
{
    protected function getQuery(): Builder
    {
        return Visit::query()
            ->select([
                DB::raw('SUM(`service_visit`.`total_cost`) AS chart_value'),
                DB::raw('MAX(`visits`.`date`) AS max_date'),
            ])
            ->join('service_visit', 'visits.id', '=', 'service_visit.visit_id')
            ->orderBy('max_date');
    }
}

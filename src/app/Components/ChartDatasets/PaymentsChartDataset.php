<?php

namespace App\Components\ChartDatasets;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Payment;

class PaymentsChartDataset extends AbstractChartDataset
{
    protected function getQuery(): Builder
    {
        return Payment::query()
            ->select([
                DB::raw('SUM(`amount`) AS chart_value'),
                DB::raw('MAX(`date`) AS max_date'),
            ])
            ->orderBy('max_date');
    }
}

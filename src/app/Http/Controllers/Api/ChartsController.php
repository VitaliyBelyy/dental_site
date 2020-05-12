<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Components\FilterBag;
use App\Components\ChartDatasets\VisitsChartDataset;
use App\Components\ChartDatasets\PaymentsChartDataset;
use App\Components\ChartDatasets\ServicesCostChartDataset;
use App\Components\ChartDatasets\RatioChartDataset;
use App\Components\Filters\PeriodFilter;
use App\Components\Filters\PeriodTypeFilter;
use App\Components\Filters\UserFilter;
use App\Components\Filters\PatientFilter;
use App\Http\Requests\Charts\ShowVisits as ChartsShowVisits;
use App\Http\Requests\Charts\ShowPayments as ChartsShowPayments;
use App\Http\Requests\Charts\ShowServicesCost as ChartsShowServicesCost;
use App\Http\Requests\Charts\ShowRatio as ChartsShowRatio;

class ChartsController extends Controller
{
    private function getFilterBag(array $params): FilterBag
    {
        $filters = [];

        if (isset($params['patient_id'])) {
            $filters[] = new PatientFilter($params['patient_id'], 'patient_id');
        } elseif (isset($params['user_id'])) {
            $filters[] = new UserFilter($params['user_id'], 'patient_id');
        }

        $filters[] = new PeriodFilter($params['start_date'], $params['end_date'], 'date');
        $filters[] = new PeriodTypeFilter($params['period'], 'date');

        return new FilterBag($filters);
    }

    public function showVisits(ChartsShowVisits $request)
    {
        $filterBag = $this->getFilterBag($request->all());
        $dataset = new VisitsChartDataset($filterBag);

        return response()->api([
            'data' => $dataset->get(),
        ]);
    }

    public function showPayments(ChartsShowPayments $request)
    {
        $filterBag = $this->getFilterBag($request->all());
        $dataset = new PaymentsChartDataset($filterBag);

        return response()->api([
            'data' => $dataset->get(),
        ]);
    }

    public function showServicesCost(ChartsShowServicesCost $request)
    {
        $filterBag = $this->getFilterBag($request->all());
        $dataset = new ServicesCostChartDataset($filterBag);

        return response()->api([
            'data' => $dataset->get(),
        ]);
    }

    public function showRatio(ChartsShowRatio $request)
    {
        $dataset = new RatioChartDataset($request->get('type'));

        return response()->api([
            'data' => $dataset->get(),
        ]);
    }
}

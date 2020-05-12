<?php

namespace App\Components\ChartDatasets;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use App\Components\FilterBag;
use App\Components\Filters\PeriodTypeFilter;

abstract class AbstractChartDataset
{
    protected $filterBag;

    public function __construct(FilterBag $filterBag = null)
    {
        $this->filterBag = clone $filterBag;
    }

    public function get(): ?array
    {
        $query = $this->getQuery();
        $query = $this->applyFilters($query);
        $preparedData = $this->prepareData($query->get());

        return empty($preparedData) ? null : $preparedData;
    }

    abstract protected function getQuery(): Builder;

    protected function prepareData(Collection $data) {
        $preparedData = [];
        $periodFilter = $this->filterBag->getPeriodFilter();
        $periodTypeFilter = $this->filterBag->getPeriodTypeFilter();
        $startDate = Carbon::parse($periodFilter->getStartDate());
        $endDate = Carbon::parse($periodFilter->getEndDate());
        $periodType = $periodTypeFilter->getPeriodType();

        switch($periodType) {
            case PeriodTypeFilter::DAILY_PERIOD:
                $dates = (new CarbonPeriod($startDate, "1 {$periodType}", $endDate))->toArray();
                for ($i = 0; $i < count($dates); $i++) {
                    $currentDate = $dates[$i];
                    $key = $currentDate->copy()->format('F jS Y');
                    $index = $data->search(function ($item) use ($currentDate) {
                        return Carbon::parse($item->max_date)->eq($currentDate->copy());
                    });
                    $preparedData[$key] = $index !== false ? $data->get($index)->chart_value : null;
                }
                break;
            case PeriodTypeFilter::WEEKLY_PERIOD:
                $dates = (new CarbonPeriod($startDate->startOfWeek(), "1 {$periodType}", $endDate->endOfWeek()))->toArray();
                for ($i = 0; $i < count($dates); $i++) {
                    $currentDate = $dates[$i];
                    $key = $this->getWeekTitle($currentDate);
                    $index = $data->search(function ($item) use ($currentDate) {
                        return Carbon::parse($item->max_date)->between($currentDate->copy()->startOfWeek(), $currentDate->copy()->endOfWeek());
                    });
                    $preparedData[$key] = $index !== false ? $data->get($index)->chart_value : null;
                }
                break;
            case PeriodTypeFilter::MONTHLY_PERIOD:
                $dates = (new CarbonPeriod($startDate->startOfMonth(), "1 {$periodType}", $endDate->endOfMonth()))->toArray();
                for ($i = 0; $i < count($dates); $i++) {
                    $currentDate = $dates[$i];
                    $key = $currentDate->copy()->format('F Y');
                    $index = $data->search(function ($item) use ($currentDate) {
                        return Carbon::parse($item->max_date)->between($currentDate->copy()->startOfMonth(), $currentDate->copy()->endOfMonth());
                    });
                    $preparedData[$key] = $index !== false ? $data->get($index)->chart_value : null;
                }
                break;
        }

        return $preparedData;
    }

    protected function getWeekTitle(CarbonInterface $date): string
    {
        $startDate = $date->copy()->startOfWeek();
        $endDate = $date->copy()->endOfWeek();
        $startMonth = $startDate->format('F');
        $endMonth = $endDate->format('F');
        $suffixMonth = $startMonth === $endMonth ? '' : $endMonth;
        $startYear = $startDate->format('Y');
        $endYear = $endDate->format('Y');
        $suffixYear = $startYear === $endYear ? '' : $endYear;
        return $suffixYear ? "{$startMonth} {$startDate->format('jS')} {$startYear} - {$suffixMonth} {$endDate->format('jS')} {$suffixYear}" :
            "{$startMonth} {$startDate->format('jS')} - {$suffixMonth} {$endDate->format('jS')} {$startYear}";
    }

    protected function applyFilters(Builder &$query, array $filters = null): Builder
    {
        if(!$filters) {
            $filters = $this->getFilters();
        }
        foreach ($filters as $filter) {
            $query = $filter->apply($query);
        }

        return $query;
    }

    private function getFilters(): array
    {
        return optional($this->filterBag)->getFilters() ?? [];
    }
}

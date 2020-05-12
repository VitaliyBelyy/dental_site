<?php

namespace App\Components;

use App\Components\Filters\PeriodFilter;
use App\Components\Filters\PeriodTypeFilter;

class FilterBag
{
    private $filters = [];

    public function __construct(array $filters = [])
    {
        $this->addFilters($filters);
    }

    public function addFilters(array $filters)
    {
        foreach ($filters as $filter) {
            $this->filters[$filter->getName()] = $filter;
        }
    }

    public function getFilters(): array
    {
        return $this->filters;
    }

    public function getPeriodFilter(): ?PeriodFilter
    {
        return data_get($this->getFilters(), 'period');
    }

    public function getPeriodTypeFilter(): ?PeriodTypeFilter
    {
        return data_get($this->getFilters(), 'period_type');
    }
}

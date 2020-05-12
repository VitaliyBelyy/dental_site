<?php

namespace App\Components\Filters;

use Illuminate\Database\Eloquent\Builder;

class PeriodFilter
{
    private $startDate;

    private $endDate;

    private $fieldName;

    public function __construct(string $startDate, string $endDate, string $fieldName)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->fieldName = $fieldName;
    }

    public function apply(Builder &$query): Builder
    {
        $query->whereDate($this->fieldName, '>=', $this->startDate)
            ->whereDate($this->fieldName, '<=', $this->endDate);

        return $query;
    }

    public function getStartDate(): string
    {
        return $this->startDate;
    }

    public function getEndDate(): string
    {
        return $this->endDate;
    }

    public function getName(): string
    {
        return 'period';
    }
}

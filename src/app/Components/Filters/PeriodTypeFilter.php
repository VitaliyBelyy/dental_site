<?php

namespace App\Components\Filters;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class PeriodTypeFilter
{
    const DAILY_PERIOD = 'day';
    const WEEKLY_PERIOD = 'week';
    const MONTHLY_PERIOD = 'month';

    const PERIODS = [
        self::DAILY_PERIOD,
        self::WEEKLY_PERIOD,
        self::MONTHLY_PERIOD,
    ];

    private $periodType;

    private $fieldName;

    public function __construct(string $periodType, string $fieldName)
    {
        $this->periodType = $periodType;
        $this->fieldName = $fieldName;
    }

    public function apply(Builder &$query): Builder
    {
        switch($this->periodType) {
            case self::DAILY_PERIOD:
                $query->groupBy(DB::raw("DATE({$this->fieldName})"));
                break;
            case self::WEEKLY_PERIOD:
                $query->groupBy(DB::raw("YEAR({$this->fieldName})"), DB::raw("WEEKOFYEAR({$this->fieldName})"));
                break;
            case self::MONTHLY_PERIOD:
                $query->groupBy(DB::raw("DATE_FORMAT({$this->fieldName}, '%Y-%m')"));
                break;
        }

        return $query;
    }

    public function getPeriodType()
    {
        return $this->periodType;
    }

    public function getName(): string
    {
        return 'period_type';
    }
}

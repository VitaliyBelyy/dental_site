<?php

namespace App\Components\Filters;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class PeriodTypeFilter
{
    const DAILY_PERIOD = 0;
    const WEEKLY_PERIOD = 1;
    const MONTHLY_PERIOD = 2;

    const PERIODS = [
        self::DAILY_PERIOD,
        self::WEEKLY_PERIOD,
        self::MONTHLY_PERIOD,
    ];

    const PERIODS_WORD_MAP = [
        self::DAILY_PERIOD => 'day',
        self::WEEKLY_PERIOD => 'week',
        self::MONTHLY_PERIOD => 'month'
    ];

    private $periodType;

    private $fieldName;

    public function __construct(int $periodType, string $fieldName)
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

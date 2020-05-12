<?php

namespace App\Components\ChartDatasets;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Patient;

class RatioChartDataset
{
    const AGE = 0;
    const GENDER = 1;

    const TYPES = [
        self::AGE,
        self::GENDER,
    ];

    protected $type;

    public function __construct(int $type)
    {
        $this->type = $type;
    }

    public function get(): ?array
    {
        $query = $this->getQuery();
        $preparedData = $this->prepareData($query->get());

        return empty($preparedData) ? null : $preparedData;
    }

    protected function getQuery(): Builder
    {
        if ($this->type === self::GENDER) {
            return Patient::query()
                ->select([
                    DB::raw('gender AS chart_value'),
                    DB::raw('COUNT(*) AS count'),
                ])
                ->groupBy('gender');
        }

        return Patient::query()
                ->select([
                    DB::raw('birth_date AS chart_value'),
                    DB::raw('COUNT(*) AS count'),
                ])
                ->groupBy('birth_date')
                ->orderBy('chart_value', 'desc');
    }

    protected function prepareData(Collection $data) {
        $preparedData = [];

        $data->each(function ($item, $key) use (&$preparedData) {
            $label = $this->getLabel($item->chart_value);
            $preparedData[$label] = isset($preparedData[$label]) ? $preparedData[$label] + $item->count : $item->count;
        });

        return $preparedData;
    }

    protected function getLabel($value): string
    {
        if ($this->type === self::GENDER) {
            switch($value) {
                case Patient::MALE:
                    return 'Male';
                case Patient::FEMALE:
                    return 'Female';
            }
        }

        $age = Carbon::parse($value)->age;
        switch(true) {
            case $age < 18:
                return '< 18';
            case $age >= 18 && $age <= 44:
                return '18 - 44';
            case $age >= 45 && $age <= 59:
                return '45 - 59';
            case $age >= 60 && $age <= 74:
                return '60 - 74';
            case $age >= 75 && $age <= 90:
                return '75 - 90';
            case $age > 90:
                return '> 90';
        }
    }
}

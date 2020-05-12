<?php

namespace App\Components\Filters;

use Illuminate\Database\Eloquent\Builder;

class PatientFilter
{
    private $patientId;

    private $fieldName;

    public function __construct(int $patientId, string $fieldName)
    {
        $this->patientId = $patientId;
        $this->fieldName = $fieldName;
    }

    public function apply(Builder &$query): Builder
    {
        $query->where($this->fieldName, $this->patientId);

        return $query;
    }

    public function getName(): string
    {
        return 'patient';
    }
}

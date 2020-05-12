<?php

namespace App\Components\Filters;

use Illuminate\Database\Eloquent\Builder;
use App\Models\User;

class UserFilter
{
    private $userId;

    private $fieldName;

    public function __construct(int $userId, string $fieldName)
    {
        $this->userId = $userId;
        $this->fieldName = $fieldName;
    }

    public function apply(Builder &$query): Builder
    {
        $patients = User::findOrFail($this->userId)
            ->patients
            ->pluck('id');

        $query->whereIn($this->fieldName, $patients);

        return $query;
    }

    public function getName(): string
    {
        return 'patient';
    }
}

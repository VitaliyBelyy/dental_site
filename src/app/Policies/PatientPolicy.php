<?php

namespace App\Policies;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PatientPolicy
{
    use HandlesAuthorization;

    public function viewList(User $user)
    {
        return $user->hasPermissionTo('patients.viewList');
    }

    public function store(User $user)
    {
        return $user->hasPermissionTo('patients.store');
    }

    public function view(User $user, Patient $patient)
    {
        return $user->hasPermissionTo('patients.view') && $user->id === $patient->user_id;
    }

    public function update(User $user, Patient $patient)
    {
        return $user->hasPermissionTo('patients.update') && $user->id === $patient->user_id;
    }

    public function viewVisitHistory(User $user, Patient $patient)
    {
        return $user->hasPermissionTo('patients.viewVisitHistory') && $user->id === $patient->user_id;
    }

    public function createVisitHistoryRecord(User $user, Patient $patient)
    {
        return $user->hasPermissionTo('patients.createVisitHistoryRecord') && $user->id === $patient->user_id;
    }

    public function viewPaymentHistory(User $user, Patient $patient)
    {
        return $user->hasPermissionTo('patients.viewPaymentHistory') && $user->id === $patient->user_id;
    }

    public function createPaymentHistoryRecord(User $user, Patient $patient)
    {
        return $user->hasPermissionTo('patients.createPaymentHistoryRecord') && $user->id === $patient->user_id;
    }

    public function viewServiceHistory(User $user, Patient $patient)
    {
        return $user->hasPermissionTo('patients.viewServiceHistory') && $user->id === $patient->user_id;
    }
}

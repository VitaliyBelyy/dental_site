<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::updateOrCreate(['name' => 'admin']);

        $user = Role::firstOrCreate(['name' => 'user']);

        $permissionsList = [
            'patients.viewList',
            'patients.store',
            'patients.view',
            'patients.update',
            'patients.viewServiceHistory',
            'patients.createServiceHistoryRecord',
            'patients.viewPaymentHistory',
            'patients.createPaymentHistoryRecord',
        ];

        $user->syncPermissions(Permission::whereIn('name', $permissionsList)->get());
    }
}

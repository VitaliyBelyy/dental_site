<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            ['name' => 'users.viewList'],
            ['name' => 'users.store'],
            ['name' => 'users.view'],
            ['name' => 'users.update'],
            ['name' => 'users.destroy'],

            ['name' => 'patients.viewList'],
            ['name' => 'patients.store'],
            ['name' => 'patients.view'],
            ['name' => 'patients.update'],
            ['name' => 'patients.viewVisitHistory'],
            ['name' => 'patients.createVisitHistoryRecord'],
            ['name' => 'patients.viewPaymentHistory'],
            ['name' => 'patients.createPaymentHistoryRecord'],
        ];

        foreach ($permissions as $key => $value) {
            Permission::updateOrCreate($value);
        }
    }
}

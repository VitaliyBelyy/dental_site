<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::firstOrCreate(
            ['fullname' => 'admin', 'email' => 'admin@example.com'],
            ['password' => Hash::make('123456'), 'api_token' => uniqid(Str::random(60))]
        );

        $admin->assignRole('admin');

        // Create test users and assign roles to them
        // factory(User::class, 3)->create()
        //      ->each(function ($user) {
        //          $user->assignRole('user');
        //      });
    }
}

<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
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
            ['full_name' => 'admin', 'email' => 'admin@example.com'],
            [
                'email_verified_at' => now(),
                'password' => Hash::make('12345678'),
                'api_token' => uniqid(Str::random(60)),
                'remember_token' => Str::random(10)
            ]
        );

        $admin->assignRole('admin');

        // Create test users
         factory(User::class, 10)->create()
              ->each(function ($user) {
                  $user->assignRole('user');
              });
    }
}

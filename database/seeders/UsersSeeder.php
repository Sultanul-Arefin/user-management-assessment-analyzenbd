<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()
            ->count(5)
            ->create()
            ->each(static function ($user){
                User::factory()
                    ->count(3)
                    ->create([
                        'created_by' => $user->id
                    ]);

                UserAddress::factory()
                    ->count(3)
                    ->create([
                        'user_id' => $user->id
                    ]);
            });
    }
}

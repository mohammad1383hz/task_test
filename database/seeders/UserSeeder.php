<?php

namespace Database\Seeders;

use App\Models\FinancialAccount;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Create admin
        $admin = User::create(
            [
                'name' => 'admin',

                'email' => 'admin@admin.com',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('12345678')
            ]
        );
      
        // User::factory()->count(50)->create();
    }
}



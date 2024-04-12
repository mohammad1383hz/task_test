<?php

namespace Database\Seeders;

use App\Models\FinancialAccount;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Create admin
        $admin = Task::create(
            [
                'title' => 'test',

                'description' => 'test',
                'user_id' => 1,
            ]
        );
      
        // User::factory()->count(50)->create();
    }
}



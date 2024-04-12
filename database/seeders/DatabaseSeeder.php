<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\AboutSeeder;
use Database\Seeders\OrderSeeder;
use Illuminate\Support\Facades\DB;
use Database\Seeders\AddressSeeder;
use Database\Seeders\PropertySeeder;
use Database\Seeders\ProvinceSeeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
//        DB::disableQueryLog();
//        Model::unguard();

        $this->call(
            [
                UserSeeder::class,
                TaskSeeder::class,

                // CountrySeeder::class,
            ]
        );

//        Model::reguard();
//        DB::enableQueryLog();
    }
}

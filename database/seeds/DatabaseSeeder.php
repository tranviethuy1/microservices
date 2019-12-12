<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call(DataSeeder::class);
         $this->call(KPISeeder::class);
         $this->call(CriteriaSeeder::class);
         $this->call(DepartmentInfo::class);
         $this->call(UserSeeder::class);
    }
}

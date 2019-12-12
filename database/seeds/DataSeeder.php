<?php

use Illuminate\Database\Seeder;

class DataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('role')->insert([
            [
                'id' => 1,
                'id_employee' => '4614718215946240',
                'access' => 1
            ],
            [
                'id' => 2,
                'id_employee' => '4867848019116032',
                'access' => 1,
            ],
            [
                'id' => 3,
                'id_employee' => '4869265962303488',
                'access' => 1
            ],
            [
                'id' => 4,
                'id_employee' => '4909235464830976',
                'access' => 1
            ],
            [
                'id' => 5,
                'id_employee' => '4983315060752384',
                'access' => 1
            ],
            [
                'id' => 6,
                'id_employee' => '5083798974758912',
                'access' => 1
            ],
            [
                'id' => 7,
                'id_employee' => '5097363353894912',
                'access' => 1
            ],
            [
                'id' => 8,
                'id_employee' => '5097662860754944',
                'access' => 1
            ],
            [
                'id' => 9,
                'id_employee' => '5134733327466496',
                'access' => 1
            ],
            [
                'id' => 10,
                'id_employee' => '5141955650519040',
                'access' => 1
            ],
            [
                'id' => 11,
                'id_employee' => '5183170693562368',
                'access' => 1
            ],
            [
                'id' => 12,
                'id_employee' => '5201579628036096',
                'access' => 1
            ],
            [
                'id' => 13,
                'id_employee' => '5272127519326208',
                'access' => 1
            ],
            [
                'id' => 14,
                'id_employee' => '5592497719869440',
                'access' => 1
            ],
            [
                'id' => 15,
                'id_employee' => '5796125139271680',
                'access' => 1
            ],
        ]);
    }
}

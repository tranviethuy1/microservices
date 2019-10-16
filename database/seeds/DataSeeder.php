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
        DB::table('kpis')->insert([
            [
                'position'=>'Employee',
                'kpi'=> 80
            ],
            [
                'position'=>'Manager',
                'kpi'=> 96
            ],
            [
                'position'=>'CTO',
                'kpi'=> 90
            ],
            [
                'name'=>'Project Manager',
                'email'=> 90
            ]
        ]);

        DB::table('users')->insert([
            [
                'name'=>'Tran Viet Huy',
                'email'=>'huytran161297@gmail.com',
                'password'=>bcrypt('tranhuy1'),
                'phone' => '0973248051',
                'address' => 'Hung Yen',
                'birth' => '16-12-1997',
                'department' => 1,
                'id_position' => 1
            ],
            [
                'name'=>'Pham Hoan',
                'email'=>'hoanpham123@gmail.com',
                'password'=>bcrypt('hoanpham123'),
                'phone' => '0973248052',
                'address' => 'Ha Noi',
                'birth' => '11-11-1997',
                'department' => 1,
                'id_position' => 2
            ],
            [
                'name'=>'Phung Hung',
                'email'=>'hungphung123@gmail.com',
                'password'=>bcrypt('hungphung123'),
                'phone' => '0973248053',
                'address' => 'Ha Noi',
                'birth' => '11-10-1997',
                'department' => 1,
                'id_position' => 3
            ],
            [
                'name'=>'Chung Bien',
                'email'=>'chungbt123@gmail.com',
                'password'=>bcrypt('chungbt123'),
                'phone' => '0973248054',
                'address' => 'Ha Noi',
                'birth' => '11-09-1997',
                'department' => 1,
                'id_position' => 4
            ]
        ]);

        DB::table('kpi_users')->insert([
            [
                'id_user'=> 1,
                'complete_tasks'=> 90,
                'rate1'=> 50,
                'working_hours' => 85,
                'rate2' => 30,
                'time_late' => 80,
                'rate3' => 20,
                'month' => 1,
                'year' => 2019,
            ],
            [
                'id_user'=> 1,
                'complete_tasks'=> 90,
                'rate1'=> 50,
                'working_hours' => 85,
                'rate2' => 30,
                'time_late' => 80,
                'rate3' => 20,
                'month' => 2,
                'year' => 2019,
            ],
            [
                'id_user'=> 1,
                'complete_tasks'=> 90,
                'rate1'=> 50,
                'working_hours' => 80,
                'rate2' => 30,
                'time_late' => 85,
                'rate3' => 20,
                'month' => 3,
                'year' => 2019,
            ],
            [
                'id_user'=> 1,
                'complete_tasks'=> 90,
                'rate1'=> 50,
                'working_hours' => 35,
                'rate2' => 30,
                'time_late' => 25,
                'rate3' => 20,
                'month' => 4,
                'year' => 2019,
            ],
            [
                'id_user'=> 1,
                'complete_tasks'=> 50,
                'rate1'=> 50,
                'working_hours' => 50,
                'rate2' => 30,
                'time_late' => 50,
                'rate3' => 20,
                'month' => 5,
                'year' => 2019,
            ],
            [
                'id_user'=> 2,
                'complete_tasks'=> 90,
                'rate1'=> 50,
                'working_hours' => 85,
                'rate2' => 30,
                'time_late' => 80,
                'rate3' => 20,
                'month' => 1,
                'year' => 2019,
            ],
            [
                'id_user'=> 2,
                'complete_tasks'=> 90,
                'rate1'=> 50,
                'working_hours' => 85,
                'rate2' => 30,
                'time_late' => 80,
                'rate3' => 20,
                'month' => 2,
                'year' => 2019,
            ],
            [
                'id_user'=> 2,
                'complete_tasks'=> 90,
                'rate1'=> 50,
                'working_hours' => 80,
                'rate2' => 30,
                'time_late' => 85,
                'rate3' => 20,
                'month' => 3,
                'year' => 2019,
            ],
            [
                'id_user'=> 2,
                'complete_tasks'=> 90,
                'rate1'=> 50,
                'working_hours' => 35,
                'rate2' => 30,
                'time_late' => 25,
                'rate3' => 20,
                'month' => 4,
                'year' => 2019,
            ],
            [
                'id_user'=> 2,
                'complete_tasks'=> 50,
                'rate1'=> 50,
                'working_hours' => 50,
                'rate2' => 30,
                'time_late' => 50,
                'rate3' => 20,
                'month' => 5,
                'year' => 2019,
            ],
            [
                'id_user'=> 3,
                'complete_tasks'=> 90,
                'rate1'=> 50,
                'working_hours' => 85,
                'rate2' => 30,
                'time_late' => 80,
                'rate3' => 20,
                'month' => 1,
                'year' => 2019,
            ],
            [
                'id_user'=> 3,
                'complete_tasks'=> 90,
                'rate1'=> 50,
                'working_hours' => 85,
                'rate2' => 30,
                'time_late' => 80,
                'rate3' => 20,
                'month' => 2,
                'year' => 2019,
            ],
            [
                'id_user'=> 3,
                'complete_tasks'=> 90,
                'rate1'=> 50,
                'working_hours' => 80,
                'rate2' => 30,
                'time_late' => 85,
                'rate3' => 20,
                'month' => 3,
                'year' => 2019,
            ],
            [
                'id_user'=> 3,
                'complete_tasks'=> 90,
                'rate1'=> 50,
                'working_hours' => 35,
                'rate2' => 30,
                'time_late' => 25,
                'rate3' => 20,
                'month' => 4,
                'year' => 2019,
            ],
            [
                'id_user'=> 3,
                'complete_tasks'=> 50,
                'rate1'=> 50,
                'working_hours' => 50,
                'rate2' => 30,
                'time_late' => 50,
                'rate3' => 20,
                'month' => 5,
                'year' => 2019,
            ],
            [
                'id_user'=> 4,
                'complete_tasks'=> 90,
                'rate1'=> 50,
                'working_hours' => 85,
                'rate2' => 30,
                'time_late' => 80,
                'rate3' => 20,
                'month' => 1,
                'year' => 2019,
            ],
            [
                'id_user'=> 4,
                'complete_tasks'=> 90,
                'rate1'=> 50,
                'working_hours' => 85,
                'rate2' => 30,
                'time_late' => 80,
                'rate3' => 20,
                'month' => 2,
                'year' => 2019,
            ],
            [
                'id_user'=> 4,
                'complete_tasks'=> 90,
                'rate1'=> 50,
                'working_hours' => 80,
                'rate2' => 30,
                'time_late' => 85,
                'rate3' => 20,
                'month' => 3,
                'year' => 2019,
            ],
            [
                'id_user'=> 4,
                'complete_tasks'=> 90,
                'rate1'=> 50,
                'working_hours' => 35,
                'rate2' => 30,
                'time_late' => 25,
                'rate3' => 20,
                'month' => 4,
                'year' => 2019,
            ],
            [
                'id_user'=> 4,
                'complete_tasks'=> 50,
                'rate1'=> 50,
                'working_hours' => 50,
                'rate2' => 30,
                'time_late' => 50,
                'rate3' => 20,
                'month' => 5,
                'year' => 2019,
            ]
        ]);

        DB::table('evaluate')->insert([
            [
                'profits'=> 200
            ],
            [
                'profits'=> 400
            ],
            [
                'profits'=> 300
            ],
        ]);

        DB::table('department')->insert([
            [
                'name' => 'Production Room',
                'id_evaluate' => 1
            ],
            [
                'name'=>'Import Room',
                'id_evaluate' => 2
            ],
            [
                'name'=>'Maketing Room',
                'id_evaluate' => 3
            ],
        ]);

        DB::table('kpi_department')->insert([
            [
                'id_department' => 1,
                'profits' => 100,
                'month' => 1,
                'year' => 2019
            ],
            [
                'id_department' => 1,
                'profits' => 200,
                'month' => 2,
                'year' => 2019
            ],
            [
                'id_department' => 1,
                'profits' => 300,
                'month' => 3,
                'year' => 2019
            ],
            [
                'id_department' => 1,
                'profits' => 500,
                'month' => 4,
                'year' => 2019
            ],
            [
                'id_department' => 1,
                'profits' => 400,
                'month' => 5,
                'year' => 2019
            ],
            [
                'id_department' => 2,
                'profits' => 100,
                'month' => 1,
                'year' => 2019
            ],
            [
                'id_department' => 2,
                'profits' => 200,
                'month' => 2,
                'year' => 2019
            ],
            [
                'id_department' => 2,
                'profits' => 300,
                'month' => 3,
                'year' => 2019
            ],
            [
                'id_department' => 2,
                'profits' => 500,
                'month' => 4,
                'year' => 2019
            ],
            [
                'id_department' => 2,
                'profits' => 400,
                'month' => 5,
                'year' => 2019
            ],
            [
                'id_department' => 3,
                'profits' => 100,
                'month' => 1,
                'year' => 2019
            ],
            [
                'id_department' => 3,
                'profits' => 200,
                'month' => 2,
                'year' => 2019
            ],
            [
                'id_department' => 3,
                'profits' => 300,
                'month' => 3,
                'year' => 2019
            ],
            [
                'id_department' => 3,
                'profits' => 500,
                'month' => 4,
                'year' => 2019
            ],
            [
                'id_department' => 3,
                'profits' => 400,
                'month' => 5,
                'year' => 2019
            ],
        ]);
    }
}

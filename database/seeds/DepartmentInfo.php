<?php

use Illuminate\Database\Seeder;

class DepartmentInfo extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $department1 = array(
            'positions' => [
                [
                    'name' => 'Trưởng phòng',
                    'employee_id' => '4614718215946240'
                ],
                [
                    'name' => 'Phó phòng',
                    'employee_id' => '4867848019116032'
                ],
                [
                    'name' => 'Nhân viên',
                    'employee_id' => '4869265962303488'
                ],
                [
                    'name' => 'Nhân viên',
                    'employee_id' => '4909235464830976'
                ],
                [
                    'name' => 'Nhân viên',
                    'employee_id' => '4983315060752384'
                ],
                [
                    "name" => "Nhân viên",
                    "employee_id" => "5083798974758912"
                ],
                [
                    "name" => "Nhân viên",
                    "employee_id" => "5097363353894912"
                ]
            ],
            'id' => 1,
            'created_at' => '2019-05-07T05:25:03.535112+00:00',
            'department_name' => 'Ban Giám đốc',
            'updated_at' => '2019-05-07T05:25:03.535112+00:00'
        );

        $criteria1 = array(
            'department_id' => 1,
            'type' => 'DEPARTMENT',
            'updated_at' => '2019-12-12T07:49:48.455642+00:00',
            'period' => 'YEAR',
            'employee_id' => null,
            "criterias" => [
                [
                    "ratio" => 0.1,
                    "name" => "Chất lượng dự án",
                    "note" => ""
                ],
                [
                    "ratio" => 0.4,
                    "name" => "Quy mô mức độ dự án",
                    "note" => ""
                ],
                [
                    "ratio" => 0.5,
                    "name" => "Yếu tố kĩ thuật",
                    "note" => ""
                ]
            ],
            "id"=> 8,
            "created_at" => "2019-11-27T09:48:37.019854+00:00",
            "project_id" => null
        );

        $kpi = array(
            "kpi" => [
            "2019" => [
                "kpi"=> 1.91,
                    "detail"=> [
                        "11" => 4.29,
                        "1" => 2.5,
                        "12" => 3.24,
                        "2" => 2.74,
                        "3" => 3.43,
                        "4" => 2.2,
                        "5" => 2.19,
                        "6" => 2.85,
                        "7" => 2.56,
                        "8" => 2.87,
                        "9" => 1.89,
                        "10"=> 1.22
                    ]
                ],
            "2018" => [
                "kpi" => 2.34,
                    "detail" => [
                        "11" => 3.48,
                        "1" => 2.74,
                        "12" => 2.48,
                        "2" => 2.52,
                        "3" => 0.86,
                        "4" => 0.99,
                        "5" => 2.14,
                        "6" => 1.39,
                        "7" => 1.97,
                        "8" => 1.17,
                        "9" => 3.43,
                        "10" => 2.02
                    ]
                ],
            "2017"=> [
                "kpi"=> 4.33,
                    "detail"=> [
                        "11"=> 1.39,
                        "1"=> 3.67,
                        "12"=> 1.94,
                        "2"=> 3.55,
                        "3"=> 1.38,
                        "4"=> 2.01,
                        "5"=> 2.41,
                        "6"=> 1.75,
                        "7"=> 2.66,
                        "8"=> 1.98,
                        "9"=> 2.24,
                        "10"=> 2.68
                    ]
                ]
            ]
        );
        DB::table('department_info')->insert([
            [
                'id' => 1,
                'data' => json_encode($department1)
            ]
        ]);

        DB::table('department_criteria')->insert([
            [
                'id' => 1,
                'data' => json_encode($criteria1)
            ]
        ]);

        DB::table('department_kpi')->insert([
            [
                'id' => 1,
                'data' => json_encode($kpi)
            ]
        ]);
    }
}

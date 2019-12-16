<?php

use Illuminate\Database\Seeder;

class KPISeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $reality1= array(
            [
                "data"=>0.29,
                "ratio"=>0.3,
                "note"=>"",
                "name"=>"Tiến độ dự án"
            ],
            [
                "data"=>1,
                "ratio"=>0.5,
                "note"=>"",
                "name"=>"Chất lượng dự án"
            ],
            [
                "data"=>0.2,
                "ratio"=>0.2,
                "note"=>"",
                "name"=>"Quy mô mức độ dự án"
            ],
        );
        $reality2= array(
            [
                "data"=>0.15,
                "ratio"=>0.3,
                "note"=>"",
                "name"=>"Tiến độ dự án"
            ],
            [
                "data"=>1,
                "ratio"=>0.4,
                "note"=>"",
                "name"=>"Chất lượng dự án"
            ],
            [
                "data"=>0.17,
                "ratio"=>0.2,
                "note"=>"",
                "name"=>"Quy mô mức độ dự án"
            ],
            [
                "data"=>0.3,
                "ratio"=>0.1,
                "note"=>"",
                "name"=>"Yếu tố kĩ thuật"
            ],
            [
                "data"=>0.3,
                "ratio"=>0.1,
                "note"=>"",
                "name"=>"Tiêu chí 5"
            ],
            [
                "data"=>0.3,
                "ratio"=>0.1,
                "note"=>"",
                "name"=>"Tiêu chí 6"
            ]

        );
        $reality3= array(
            [
                "data"=>0.5,
                "ratio"=>0.6,
                "note"=>"",
                "name"=>"Tiến độ dự án"
            ],
            [
                "data"=>1,
                "ratio"=>0.1,
                "note"=>"",
                "name"=>"Chất lượng dự án"
            ],
            [
                "data"=>0.51,
                "ratio"=>0.2,
                "note"=>"",
                "name"=>"Quy mô mức độ dự án"
            ],
            [
                "data"=>0.8,
                "ratio"=>0.1,
                "note"=>"",
                "name"=>"Yếu tố kĩ thuật"
            ]

        );

        $reality4= array(
            [
                "data"=>0.03,
                "ratio"=>0.3,
                "note"=>"",
                "name"=>"Tiến độ dự án"
            ],
            [
                "data"=>1,
                "ratio"=>0.4,
                "note"=>"",
                "name"=>"Chất lượng dự án"
            ],
            [
                "data"=>0.36,
                "ratio"=>0.2,
                "note"=>"",
                "name"=>"Quy mô mức độ dự án"
            ],
            [
                "data"=>0.6,
                "ratio"=>0.1,
                "note"=>"",
                "name"=>"Yếu tố kĩ thuật"
            ]

        );

        $standard1= array(
            [
                "data"=>0.3,
                "ratio"=>0.3,
                "note"=>"",
                "name"=>"Tiến độ dự án"
            ],
            [
                "data"=>1,
                "ratio"=>0.5,
                "note"=>"",
                "name"=>"Chất lượng dự án"
            ],
            [
                "data"=>0.2,
                "ratio"=>0.2,
                "note"=>"",
                "name"=>"Quy mô mức độ dự án"
            ],
        );
        $standard2= array(
            [
                "data"=>0.3,
                "ratio"=>0.3,
                "note"=>"",
                "name"=>"Tiến độ dự án"
            ],
            [
                "data"=>1,
                "ratio"=>0.4,
                "note"=>"",
                "name"=>"Chất lượng dự án"
            ],
            [
                "data"=>0.17,
                "ratio"=>0.2,
                "note"=>"",
                "name"=>"Quy mô mức độ dự án"
            ],
            [
                "data"=>0.5,
                "ratio"=>0.1,
                "note"=>"",
                "name"=>"Yếu tố kĩ thuật"
            ],
            [
                "data"=>0.3,
                "ratio"=>0.1,
                "note"=>"",
                "name"=>"Tiêu chí 5"
            ],
            [
                "data"=>0.5,
                "ratio"=>0.1,
                "note"=>"",
                "name"=>"Tiêu chí 6"
            ]

        );
        $standard3= array(
            [
                "data"=>0.3,
                "ratio"=>0.6,
                "note"=>"",
                "name"=>"Tiến độ dự án"
            ],
            [
                "data"=>1,
                "ratio"=>0.1,
                "note"=>"",
                "name"=>"Chất lượng dự án"
            ],
            [
                "data"=>0.51,
                "ratio"=>0.2,
                "note"=>"",
                "name"=>"Quy mô mức độ dự án"
            ],
            [
                "data"=>0.8,
                "ratio"=>0.1,
                "note"=>"",
                "name"=>"Yếu tố kĩ thuật"
            ]

        );

        $standard4= array(
            [
                "data"=>0.03,
                "ratio"=>0.3,
                "note"=>"",
                "name"=>"Tiến độ dự án"
            ],
            [
                "data"=>1,
                "ratio"=>0.4,
                "note"=>"",
                "name"=>"Chất lượng dự án"
            ],
            [
                "data"=>0.36,
                "ratio"=>0.2,
                "note"=>"",
                "name"=>"Quy mô mức độ dự án"
            ],
            [
                "data"=>0.6,
                "ratio"=>0.1,
                "note"=>"",
                "name"=>"Yếu tố kĩ thuật"
            ]

        );

        DB::table('kpi_fake_tables')->insert([
            [

                'project_id'=>'11',
                'criteria_id'=>'1',
                'name'=> 'Nhập các thiết bị sản xuất từ đối tác Đức',
                'kpi'=>'0.57',
                'kpi_standard'=> '0.78',
                'reality'=>json_encode($reality1),
                'standard'=>json_encode($standard1),
                'status'=> 'Bad',
                'created_time'=> date('2019-12-07 06:40:56'),
                'complete_time'=> date('2019-12-12 06:40:56'),
            ],
            [
                'project_id'=>'22',
                'criteria_id'=>'2',
                'name'=> 'Kế hoạch họp tổng kết cuối năm',
                'kpi'=>'0.51',
                'kpi_standard'=> '0.76',
                'reality'=>json_encode($reality2),
                'standard'=>json_encode($standard2),
                'status'=> 'Bad',
                'created_time'=> date('2019-12-07 06:40:56'),
                'complete_time'=> date('2019-12-12 06:40:56'),
            ],
            [
                'project_id'=>'33',
                'criteria_id'=>'3',
                'name'=> 'Bảo trì các thiết bị dây chuyền sản xuất',
                'kpi'=>'0.67',
                'kpi_standard'=> '0.88',
                'reality'=>json_encode($reality3),
                'standard'=>json_encode($standard3),
                'status'=> 'Bad',
                'created_time'=> date('2019-12-07 06:40:56'),
                'complete_time'=> date('2019-12-12 06:40:56'),
            ],[
                'project_id'=>'44',
                'criteria_id'=>'4',
                'name'=> 'Kiểm tra chất lượng thuốc Kimenas',
                'kpi'=>'0.54',
                'kpi_standard'=> '0.83',
                'reality'=>json_encode($reality4),
                'standard'=>json_encode($standard4),
                'status'=> 'Bad',
                'created_time'=> date('2019-12-07 06:40:56'),
                'complete_time'=> date('2019-12-12 06:40:56'),
            ],
        ]);
    }
}

<?php

use Illuminate\Database\Seeder;

class CriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $criteria1= array(
            [
                "ratio"=>0.3,
                "note"=>"",
                "name"=>"Tiến độ dự án"
            ],
            [
                "ratio"=>0.5,
                "note"=>"",
                "name"=>"Chất lượng dự án"
            ],
            [
                "ratio"=>0.2,
                "note"=>"",
                "name"=>"Quy mô mức độ dự án"
            ],

        );
        $criteria2= array(
            [
                "ratio"=>0.4,
                "note"=>"",
                "name"=>"Tiến độ dự án"
            ],
            [
                "ratio"=>0.1,
                "note"=>"",
                "name"=>"Chất lượng dự án"
            ],
            [
                "ratio"=>0.2,
                "note"=>"",
                "name"=>"Quy mô mức độ dự án"
            ],
            [
                "ratio"=>0.1,
                "note"=>"",
                "name"=>"Yếu tố kĩ thuật"
            ],
            [
                "ratio"=>0.1,
                "note"=>"",
                "name"=>"Tiêu chí 5"
            ],
            [
                "ratio"=>0.1,
                "note"=>"",
                "name"=>"Tiêu chí 6"
            ]

        );
        $criteria3= array(
            [
                "ratio"=>0.3,
                "note"=>"",
                "name"=>"Tiến độ dự án"
            ],
            [
                "ratio"=>0.4,
                "note"=>"",
                "name"=>"Chất lượng dự án"
            ],
            [
                "ratio"=>0.2,
                "note"=>"",
                "name"=>"Quy mô mức độ dự án"
            ],
            [
                "ratio"=>0.1,
                "note"=>"",
                "name"=>"Yếu tố kĩ thuật"
            ]

        );
        $criteria4= array(
            [
                "ratio"=>0.3,
                "note"=>"",
                "name"=>"Tiến độ dự án"
            ],
            [
                "ratio"=>0.4,
                "note"=>"",
                "name"=>"Chất lượng dự án"
            ],
            [
                "ratio"=>0.2,
                "note"=>"",
                "name"=>"Quy mô mức độ dự án"
            ],
            [
                "ratio"=>0.1,
                "note"=>"",
                "name"=>"Yếu tố kĩ thuật"
            ]

        );
        DB::table('project_criteria')->insert([
            [
                'criteria_id'=>'1',
                'data'=> json_encode($criteria1)
            ],
            [
                'criteria_id'=>'2',
                'data'=> json_encode($criteria2)
            ],
            [
                'criteria_id'=>'3',
                'data'=> json_encode($criteria3)
            ],
            [
                'criteria_id'=>'4',
                'data'=> json_encode($criteria4)
            ]
        ]);
    }
}

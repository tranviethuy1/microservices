<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user1 = array(
            "user" => [
                "address"=> "Cầu Giấy, Hà Nội",
                "birthday"=> 874597777,
                "dateCreated"=> 1575701913576,
                "email"=> "hoanglh@gmail.com",
                "employId"=> 0,
                "gender"=> "Nam",
                "id"=> 4614718215946240,
                "lastUpdate"=> 1576145444470,
                "name"=> "Lê Huy Hoàng",
                "password"=> "1",
                "phoneNumber"=> "098136661",
                "statusId"=> 1
            ],
        );

        $criteria1 = array(
            "criteriaProject"=> [
                "department_id"=> null,
                "type"=> "EMPLOYEE",
                "updated_at"=> "2019-11-27T09:48:37.020062+00:00",
                "period"=> "YEAR",
                "employee_id"=> "4614718215946240",
                "criterias"=> [
                    [
                        "note"=> "",
                        "ratio"=> 0.3,
                        "name"=> "Tiến độ dự án"
                    ],
                    [
                        "note"=> "",
                        "ratio"=> 0.4,
                        "name"=> "Chất lượng dự án"
                    ],
                    [
                        "note"=> "",
                        "ratio"=> 0.2,
                        "name"=> "Quy mô mức độ dự án"
                    ],
                    [
                        "note"=> "",
                        "ratio"=> 0.1,
                        "name"=> "Yếu tố kĩ thuật"
                    ]
                ],
                "id"=> 207,
                "created_at"=> "2019-11-27T09:48:37.020053+00:00",
                "project_id"=> "1664a77a-9c82-46af-83fe-b05b5e2e4bf8"
            ],
        );

        $kpi= array(
            "kpi"=> [
                "id"=> "1664a77a-9c82-46af-83fe-b05b5e2e4bf8",
                "name"=> "Tuyển nhân sự phòng nghiên cứu, kinh doanh",
                "description"=> "Công ty sang năm sẽ thay đổi nâng cao quy mô sản xuất nên cần tuyển nhân sự ở các phòng nghiên cứu và kinh doanh. yêu cầu các phòng ban này nêu yêu cầu, kế hoạch tuyển giao cho phòng nhân sự thực hiện.\nQuy trình thực hiện;\n- Các phòng nghiên cứu kinh doanh đề xuất số lượng nhân sự cần tuyển thêm. Trong đó nêu rõ yêu cầu về trình độ, mức lương thưởng đề xuất, thời gian làm việc\n- Các phòng nghiên cứu kinh doanh chuyển đề xuất này cho phòng nhân sự\n- Phòng nhân sự lên kế hoạch, đề xuất sửa đổi nội dung tuyển nhân sự, thực hiện công tác truyền thông, lên lịch phỏng vấn các ứng viên, báo lại cho các phòng nghiên cứu, kinh doanh\n- Các phòng nghiên cứu kinh doanh cử người phỏng vấn theo lịch bên nhân sự đã sắp xếp. Báo lại kết quả phỏng vấn cho bên nhân sự\n- Bên nhân sự thông báo kết quả đến ứng viên và thực hiện các hồ sơ nhân viên mới theo quy định",
                "tasks"=> [
                    [
                        "name"=> "Hoàn thành",
                        "description"=> "Hoàn thành hồ sơ thủ tục để nhân viên trúng tuyển có thể đến làm việc",
                        "complete_percent"=> 0
                    ],
                    [
                        "name"=> "Phỏng vấn",
                        "description"=> "Các phòng ban cần tuyển nhân sự phỏng vấn và thông báo kết quả đến các ứng viên cũng như phòng Nhân sự",
                        "complete_percent"=> 0
                    ]
                ],
                "KPI"=> 157.775,
                "created_at"=> "2019-12-07 06:42:32",
                "updated_at"=> "2019-12-12 06:42:32",
                "finished_at"=> "1970-01-01 00:00:00",
                "required_at"=> "2019-12-31 00:00:00"
            ]
        );

        DB::table('user_info')->insert([
            [
                'id' => 1,
                'data' => json_encode($user1)
            ]
        ]);

        DB::table('user_criteria')->insert([
            [
                'id' => 1,
                'data' => json_encode($criteria1)
            ]
        ]);

        DB::table('user_kpi')->insert([
            [
                'id' => 1,
                'data' => json_encode($kpi)
            ]
        ]);

    }
}

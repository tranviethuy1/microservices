<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class KpiController extends Controller
{
    public function evaluateKpiUser(Request $request){
        $user_id = $request->id_user;
        $month = $request->month;
        $year = $request->year;
        $data = [];
        try{
            $user = \App\User::find($user_id)->first();
            $kpiOfUser = \App\KpiUser::where('id_user', $user_id)
                ->where('month', $month)->where('year', $year)->first();

            if(!isset($kpiOfUser) || empty($kpiOfUser)){
                return response()->json(['error' =>['message' => 'No data in this month and this year']], 200);
            }

            $evaluate = \App\Kpi::find($user->id_position)->first();

            if(!isset($evaluate) || empty($evaluate)){
                return response()->json(['error' =>['message' => 'Somthing was wrong with position of user']], 200);
            }

            $kpi = $evaluate->kpi;

            $point = ($kpiOfUser->complete_tasks*$kpiOfUser->rate1 +
                        $kpiOfUser->working_hours*$kpiOfUser->rate2 +
                        $kpiOfUser->time_late*$kpiOfUser->rate3)/100;

            if($point >= $kpi){
                $data['status'] =  'good';
            }else{
                $data['status'] = 'bad';
            }

            $data['user'] = $user;
            $data['evaluate'] = $evaluate;
            $data['kpi'] = $kpiOfUser;
            return response()->json(['success' => 1, 'data' => $data], 200);

        }catch (\Exception $e){
            return response()->json(['error' => ['message' => 'Something was wrong with evaluating kpi of user']], 200);
        }
    }

    public function evaluateKpiAllUsers(){
        try{
            $users = DB::table('users')->get();
            $data = [];
            $i = 1;
            foreach ($users as $user) {
                $evaluate = \App\Kpi::find($user->id_position)->first();
                $value = ['user' => $user, 'evaluate' => $evaluate];
                if(isset($evaluate) && !empty($evaluate)){
                    $kpisOfUser = \App\KpiUser::where('id_user', $user->id)->get();
                    $j = 1;
                    foreach ($kpisOfUser as $kpiOfUser){
                        $month = $kpiOfUser->month;
                        $year = $kpiOfUser->year;
                        $result = [];
                        $kpiPoint = $evaluate->kpi;

                        $point = ($kpiOfUser->complete_tasks*$kpiOfUser->rate1 +
                                $kpiOfUser->working_hours*$kpiOfUser->rate2 +
                                $kpiOfUser->time_late*$kpiOfUser->rate3)/100;

                        if($point >= $kpiPoint){
                            $result['status'] =  'good';
                        }else{
                            $result['status'] = 'bad';
                        }

                        $result['kpi_of_user'] = $kpiOfUser;
                        $result['month'] = $month;
                        $result['year'] = $year;
                        $value[$j]['result'] = $result;
                        $j++;
                    }
                }
                $data[$i] = $value;
                $i++;
            }
            return response()->json(['success' => 1, 'data' => $data], 200);
        }catch (\Exception $e){
            return response()->json(['error' => ['message' => 'Something was wrong with evaluating kpi of users']], 200);
        }
    }

    public function evaluateKpiDepartment(Request $request){
        $department_id = $request->id_department;
        $month = $request->month;
        $year = $request->year;
        $data = [];
        try{
            $department = \App\Department::find($department_id)->first();
            $kpiOfDepartment = \App\KpiDepartment::where('id_department', $department_id)
                ->where('month', $month)->where('year', $year)->first();

            if(!isset($kpiOfDepartment) || empty($kpiOfDepartment)){
                return response()->json(['error' =>['message' => 'No data in this month and this year']], 200);
            }

            $evaluate = \App\Evaluate::find($department->id_evaluate)->first();

            if(!isset($evaluate) || empty($evaluate)){
                return response()->json(['error' =>['message' => 'Somthing was wrong with department']], 200);
            }

            $profits = $evaluate->profits;

            if($kpiOfDepartment->profits >= $profits){
                $data['status'] =  'good';
            }else{
                $data['status'] = 'bad';
            }

            $data['department'] = $department;
            $data['evaluate'] = $evaluate;
            $data['kpi'] = $kpiOfDepartment;
            return response()->json(['success' => 1, 'data' => $data], 200);

        }catch (\Exception $e){
            return response()->json(['error' => ['message' => 'Something was wrong with evaluating kpi of department']], 200);
        }
    }

    public function evaluateKpiAllDepartments(){
        try{
            $departments = DB::table('department')->get();
            $data = [];
            $i = 1;
            foreach ($departments as $department) {
                $evaluate = \App\Evaluate::find($department->id_evaluate)->first();
                $value = ['user' => $department, 'evaluate' => $evaluate];
                if(isset($evaluate) && !empty($evaluate)){
                    $kpisOfDeparment = \App\KpiDepartment::where('id_department', $department->id)->get();
                    $j = 1;
                    foreach ($kpisOfDeparment as $kpiOfDeparment){
                        $month = $kpiOfDeparment->month;
                        $year = $kpiOfDeparment->year;
                        $result = [];
                        $kpiPoint = $evaluate->profits;

                        $point = $kpiOfDeparment->profits;

                        if($point >= $kpiPoint){
                            $result['status'] =  'good';
                        }else{
                            $result['status'] = 'bad';
                        }

                        $result['kpi_of_department'] = $kpiOfDeparment;
                        $result['month'] = $month;
                        $result['year'] = $year;
                        $value[$j]['result'] = $result;
                        $j++;
                    }
                }
                $data[$i] = $value;
                $i++;
            }
            return response()->json(['success' => 1, 'data' => $data], 200);
        }catch (\Exception $e){
            return response()->json(['error' => ['message' => 'Something was wrong with evaluating kpi of departments']], 200);
        }
    }
}

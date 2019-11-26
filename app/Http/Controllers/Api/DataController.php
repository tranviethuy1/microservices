<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
class DataController extends Controller
{
    public function managerDepartment(Request $request){
        $id_department = $request->id_department;
        $data = [];
        //get department's information
        try{
            $urlGetDepartmentInfo = 'http://206.189.34.124:5000/api/group8/departments/'.$id_department;
            $clientInfo = new Client();
            $response = $clientInfo->request('GET', $urlGetDepartmentInfo);
             $department= json_decode($response->getBody()->getContents());
            $data['department'] = $department->department;
        }catch (\Exception $e){
            return response()->json(['error' => 1, 'message' => 'Something was wrong with api get department\'s information'], 200);
        }
        //get department's criteria
        try{
            $urlGetDepartmentCriteria = 'http://206.189.34.124:5000/api/group8/kpis?department_id='.$id_department;
            $clientCriteria = new Client();
            $response = $clientCriteria->request('GET', $urlGetDepartmentCriteria);
            $criteria = json_decode($response->getBody()->getContents());
            $data['criteria'] = $criteria;
        }catch (\Exception $e){
            return response()->json(['error' => 1, 'message' => 'Something was wrong with api get criteria\'s information'], 200);
        }
        //get kpi of department
        try{
            $urlGetDepartmentKpi = 'http://18.217.21.235:8083/api/v1/departmentKPI/getDepartmentKPIAllYear?departmentId='.$id_department;
            $clientKpi = new Client();
            $response = $clientKpi->request('GET', $urlGetDepartmentKpi);
            $kpiAndTime = json_decode($response->getBody()->getContents());
            $kpi = [];
            $kpiYears = json_decode(json_encode($kpiAndTime->data->timeAndKPI), true);
            $urlGetDepartmentKpiMonthInYear = 'http://18.217.21.235:8083/api/v1/departmentKPI/getDepartmentKPIAllMonthOfYear?departmentId='.$id_department.'&year=';
            foreach ($kpiYears as $year => $kpiYear){
                $urlInLoop = $urlGetDepartmentKpiMonthInYear.$year;
                $record = [] ;
                $record['kpi'] = $kpiYear;
                $responseInLoop = $clientKpi->request('GET', $urlInLoop);
                $record['detail'] = json_decode($responseInLoop->getBody()->getContents())->data->timeAndKPI;
                $kpi[$year] = $record;
            }
            $data['kpi'] = $kpi;
        }catch (\Exception $e){
            return response()->json(['error' => 1, 'message' => 'Something was wrong with api get kpi of department'], 200);
        }
        return response()->json(['success' => 1, 'data' => $data], 200);
    }
}

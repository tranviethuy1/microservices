<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
class DataController extends Controller
{
    public function managerDepartment(Request $request){
        $id_department = $request->id_department;
        $year = $request->year;
        $data = [];
        // Get department's information
        try{
            $urlGetDepartmentInfo = 'http://206.189.34.124:5000/api/group8/departments/'.$id_department;
            $clientInfo = new Client();
            $response = $clientInfo->request('GET', $urlGetDepartmentInfo);
            $department= json_decode($response->getBody()->getContents());
            $data['department'] = $department->department;
        }catch (\Exception $e){
            return response()->json(['error' => 1, 'message' => 'Something was wrong with api get department\'s information'], 200);
        }
        // Get department's criteria
        try{
            $urlGetDepartmentCriteria = 'http://206.189.34.124:5000/api/group8/kpis?department_id='.$id_department;
            $clientCriteria = new Client();
            $response = $clientCriteria->request('GET', $urlGetDepartmentCriteria);
            $criteria = json_decode($response->getBody()->getContents());
            $data['criteria'] = $criteria;
        }catch (\Exception $e){
            return response()->json(['error' => 1, 'message' => 'Something was wrong with api get criteria\'s information'], 200);
        }
        // Get kpi of department in year
        try{
            $urlGetDepartmentKpi = 'http://18.217.21.235:8083/api/v1/departmentKPI/getDepartmentKPIByYear?year='.$year.'&departmentId='.$id_department;
            $clientKpi = new Client();
            $response = $clientKpi->request('GET', $urlGetDepartmentKpi);
            $kpi = json_decode($response->getBody()->getContents());
            $data['kpi'] = $kpi;
        }catch (\Exception $e){
            return response()->json(['error' => 1, 'message' => 'Something was wrong with api get kpi of department'], 200);
        }
        return response()->json(['success' => 1, 'data' => $data], 200);
    }

    // Manage project kpi

    public function manageProjectKPI(Request $request){
        $yearInput = $request->year;

        $data = array();
        $index = 0;

        // get all project kpi
        $projectController = new ProjectController();
        $kpiProjects = $projectController->evaluateKpiAllProject();
        foreach ($kpiProjects as $kpiProject){
            $index++;
            $createBy = $kpiProject->created_by;
            $yearProject = date('Y', strtotime($createBy));
            if($yearInput == $yearProject){

                try{
                    // get information criteria
                    $clientCriterion = new Client();
                    $apiUrlCriterion = "http://206.189.34.124:5000/api/group8/kpis?project_id=".$kpiProject->id_project;
                    $responseCriterion = $clientCriterion->request('GET', $apiUrlCriterion);
                    $dataCriterion = json_decode($responseCriterion->getBody()->getContents());
                }catch (\Exception $e){
                    return response()->json(['error' => 1, 'message' => 'Something was wrong with api get criteria '], 400);
                }

                try{
                    // get information project
                    $clientProject = new Client();
                    $apiUrlProject = "http://3.1.20.54/v1/projects/".$kpiProject->id_project;
                    $responseProject = $clientProject->request('GET', $apiUrlProject);
                    $dataProjects = json_decode($responseProject->getBody()->getContents());
                }catch (\Exception $e){
                    return response()->json(['error' => 1, 'message' => 'Something was wrong with information project '], 400);
                }
                $dataProjects['kpi'] = $kpiProject->kpi;
                $dataProjects['criteria'] = $dataCriterion;
                $data[$index]['result'] = $dataProjects;
            }
        }

        return response()->json(['success' => 1, 'data' => $data], 200);

    }
}

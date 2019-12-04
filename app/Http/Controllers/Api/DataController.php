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
        // Get department's information
        try{
            $urlGetDepartmentInfo = 'http://206.189.34.124:5000/api/group8/departments/'.$id_department;
            $clientInfo = new Client();
            $response = $clientInfo->request('GET', $urlGetDepartmentInfo);
            $department= json_decode($response->getBody()->getContents());
            $data['department'] = $department->department;
        }catch (\Exception $e){
            return response()->json(['error' => 1, 'message' => 'Something was wrong with api get department\'s information'], 400);
        }
        // Get department's criteria
        try{
            $urlGetDepartmentCriteria = 'http://206.189.34.124:5000/api/group8/kpis?department_id='.$id_department;
            $clientCriteria = new Client();
            $response = $clientCriteria->request('GET', $urlGetDepartmentCriteria);
            $criteria = json_decode($response->getBody()->getContents());
            $data['criteria'] = $criteria;
        }catch (\Exception $e){
            return response()->json(['error' => 1, 'message' => 'Something was wrong with api get criteria\'s information'], 400);
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
            return response()->json(['error' => 1, 'message' => 'Something was wrong with api get kpi of department'], 400);
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
        $kpiProjects = $projectController->evaluateKpiAllProject()->getData('data')['data'];
        foreach ($kpiProjects as $kpiProject){
            $index++;
            $completedBy = $kpiProject['complete_by'];
            $yearProject = date('Y', strtotime($completedBy));
            if($yearInput == $yearProject){
                try{
                    // get information criteria
                    $clientCriterion = new Client();
                    $apiUrlCriterion = "http://206.189.34.124:5000/api/group8/kpis?project_id=".$kpiProject['id_project'];
                    $responseCriterion = $clientCriterion->request('GET', $apiUrlCriterion);
                    $dataCriterion = json_decode($responseCriterion->getBody()->getContents());
                }catch (\Exception $e){
                    return response()->json(['error' => 1, 'message' => 'Something was wrong with api get criteria '], 400);
                }

                try{
                    // get information project
                    $clientProject = new Client();
                    $apiUrlProject = "http://3.1.20.54/v1/projects/".$kpiProject['id_project'];
                    $responseProject = $clientProject->request('GET', $apiUrlProject);
                    $dataProjects = (array)json_decode($responseProject->getBody()->getContents());
                }catch (\Exception $e){
                    return response()->json(['error' => 1, 'message' => 'Something was wrong with information project '], 400);
                }
                $dataProjects['kpi'] = $kpiProject['kpi'];
                $dataProjects['kpi_standard'] = $kpiProject['kpi_standard'];
                $dataProjects['criteria'] = (array)$dataCriterion;
                $data[$index] = $dataProjects;
            }
        }

        if (empty($data)){
            return response()->json(['success' => 1, 'data' => 'Year '.$yearInput."  don't make projects "], 200);
        }
        return response()->json(['success' => 1, 'data' => $data], 200);

    }

    public function managerUserProject(Request $request){
        $id_user = $request->id_user;
        $id_project = $request->id_project;
        $data = [];
        // Get user's information
        try{
            $urlGetUserInfo = 'https://dsd05-dot-my-test-project-252009.appspot.com/user/getUserInfo?id='.$id_user;
            $clientInfo = new Client();
            $response = $clientInfo->request('GET', $urlGetUserInfo);
            $user= json_decode($response->getBody()->getContents());
            $data['user'] = $user;
        }catch (\Exception $e){
            return response()->json(['error' => 1, 'message' => 'Something was wrong with api get user\'s information'], 400);
        }

        //get user's criteria with project
        try{
            $urlGetUseCriteriaProject = 'http://206.189.34.124:5000/api/group8/kpis?project_id='.$id_project.'&employee_id='.$id_user;
            $clientCriteriaProject = new Client();
            $responseCriteriaProject = $clientCriteriaProject->request('GET', $urlGetUseCriteriaProject);
            $criteriaProject= json_decode($responseCriteriaProject->getBody()->getContents());
            $data['criteriaProject'] = $criteriaProject;
        }catch (\Exception $e){
            return response()->json(['error' => 1, 'message' => 'Something was wrong with api get user\'s Criteria with project'], 400);
        }

        //get kpi user
        try{
            $urlUserKpi = 'https://calm-basin-00803.herokuapp.com/api/v1/users/projects/'.$id_user.'?project_id='.$id_project;
            $clientUserKpi = new Client();
            $responseUserKpi = $clientUserKpi->request('GET', $urlUserKpi);
            $kpiUser= json_decode($responseUserKpi->getBody()->getContents());
            $data['kpi'] = $kpiUser->data->project;
        }catch (\Exception $e){
            return response()->json(['error' => 1, 'message' => 'Something was wrong with api get user\'s kpi data'], 400);
        }

        return response()->json(['success' => 1, 'data' => $data], 200);
    }

    public function managerUser(Request $request){
        $id_user = $request->id_user;
        $data = [];
        // Get user's information
        try{
            $urlGetUserInfo = 'https://dsd05-dot-my-test-project-252009.appspot.com/user/getUserInfo?id='.$id_user;
            $clientInfo = new Client();
            $response = $clientInfo->request('GET', $urlGetUserInfo);
            $user= json_decode($response->getBody()->getContents());
            $data['user'] = $user;
        }catch (\Exception $e){
            return response()->json(['error' => 1, 'message' => 'Something was wrong with api get user\'s information'], 400);
        }

        //get kpis user
        try{
            $urlUserKpi = 'https://calm-basin-00803.herokuapp.com/api/v1/users/projects/'.$id_user.'/?from=20191001&to=20191230';
            $clientUserKpi = new Client();
            $responseUserKpi = $clientUserKpi->request('GET', $urlUserKpi);
            $kpiUser= json_decode($responseUserKpi->getBody()->getContents());
            $data['kpi'] = $kpiUser->data->projects;
        }catch (\Exception $e){
            return response()->json(['error' => 1, 'message' => 'Something was wrong with api get user\'s kpi data'], 400);
        }

        return response()->json(['success' => 1, 'data' => $data], 200);
    }
}

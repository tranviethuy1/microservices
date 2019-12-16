<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
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
            $department = DB::table('department_info')->where('id',1)->first();
            $data['department'] = json_decode($department->data);
        }
        // Get department's criteria
        try{
            $urlGetDepartmentCriteria = 'http://206.189.34.124:5000/api/group8/kpis?department_id='.$id_department;
            $clientCriteria = new Client();
            $response = $clientCriteria->request('GET', $urlGetDepartmentCriteria);
            $criteria = json_decode($response->getBody()->getContents());
            $data['criteria'] = $criteria;
        }catch (\Exception $e){
            $criteria = DB::table('department_criteria')->where('id',1)->first();
            $data['criteria'] = json_decode($criteria->data);
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
            $kpi = DB::table('department_kpi')->where('id',1)->first();
            $data['kpi'] = json_decode($kpi->data);;
        }
        return response()->json(['success' => 1, 'data' => $data], 200);
    }

    public function managerDepartments(Request $request){
        $data = [];
        $ids = [];
        //get all department in system
        try{
            $urlGetAllDepartment = 'http://206.189.34.124:5000/api/group8/departments';
            $clientAllDepartment = new Client();
            $response = $clientAllDepartment->request('GET', $urlGetAllDepartment);
            $departments = json_decode($response->getBody()->getContents())->departments;
            foreach ($departments as $department){
                $ids[] = $department->id;
            }
        }catch (\Exception $e) {
            $ids = [1,1,1];
        }

        //loop
        foreach ($ids as $id_department){
        // Get department's information
            try{
                $urlGetDepartmentInfo = 'http://206.189.34.124:5000/api/group8/departments/'.$id_department;
                $clientInfo = new Client();
                $response = $clientInfo->request('GET', $urlGetDepartmentInfo);
                $department= json_decode($response->getBody()->getContents());
                $data[$id_department]['department'] = $department->department;
            }catch (\Exception $e){
                $department = DB::table('department_info')->where('id',1)->first();
                $data[$id_department]['department'] = json_decode($department->data);
            }
            // Get department's criteria
            try{
                $urlGetDepartmentCriteria = 'http://206.189.34.124:5000/api/group8/kpis?department_id='.$id_department;
                $clientCriteria = new Client();
                $response = $clientCriteria->request('GET', $urlGetDepartmentCriteria);
                $criteria = json_decode($response->getBody()->getContents());
                $data[$id_department]['criteria'] = $criteria;
            }catch (\Exception $e){
                $criteria = DB::table('department_criteria')->where('id',1)->first();
                $data[$id_department]['criteria'] = json_decode($criteria->data);
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
                $data[$id_department]['kpi'] = $kpi;
            }catch (\Exception $e){
                $kpi = DB::table('department_kpi')->where('id',1)->first();
                $data[$id_department]['kpi'] = json_decode($kpi->data);
            }
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
        // get all value of database
        $kpiProjects = $projectController->evaluateKpiAllProject()->getData('data')['data'];
        foreach ($kpiProjects as $kpiProject){
            $index++;
            $completedBy = $kpiProject['complete_time'];
            $reality = $kpiProject['reality'];
            $standard = $kpiProject['standard'];
            $yearProject = date('Y', strtotime($completedBy));
            if($yearInput == $yearProject){
                if($projectController->checkConnectAllApi()){
                    // get information project
                    $clientProject = new Client();
                    $apiUrlProject = "http://3.1.20.54/v1/projects/".$kpiProject['id_project'];
                    $responseProject = $clientProject->request('GET', $apiUrlProject);
                    $dataProjects = (array)json_decode($responseProject->getBody()->getContents());
                    // get information criteria
                    $clientCriterion = new Client();
                    $apiUrlCriterion = "http://206.189.34.124:5000/api/group8/kpis?project_id=".$kpiProject['id_project'];
                    $responseCriterion = $clientCriterion->request('GET', $apiUrlCriterion);
                    $dataCriterion = json_decode($responseCriterion->getBody()->getContents());
                }else{
                    $dataProjects = (array)DB::table('kpi_fake_tables')->where('project_id',$kpiProject['id_project'])->first();
                    $dataCriterionDb = DB::table('project_criteria')->where('criteria_id',$kpiProject['id_criteria'])->first();
                    $dataCriterion = json_decode($dataCriterionDb->data);
                }
                $dataProjects['kpi'] = $kpiProject['kpi'];
                $dataProjects['kpi_standard'] = $kpiProject['kpi_standard'];
                $dataProjects['criteria'] = (array)$dataCriterion;
                $dataProjects['reality'] = (array)$reality;
                $dataProjects['standard'] = (array)$standard;
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
            $user = DB::table('user_info')->where('id',1)->first();
            $data['user'] = json_decode($user->data);
        }

        //get user's criteria with project
        try{
            $urlGetUseCriteriaProject = 'http://206.189.34.124:5000/api/group8/kpis?project_id='.$id_project.'&employee_id='.$id_user;
            $clientCriteriaProject = new Client();
            $responseCriteriaProject = $clientCriteriaProject->request('GET', $urlGetUseCriteriaProject);
            $criteriaProject= json_decode($responseCriteriaProject->getBody()->getContents());
            $data['criteriaProject'] = $criteriaProject;
        }catch (\Exception $e){
            $criteriaProject = DB::table('user_criteria')->where('id',1)->first();
            $data['criteriaProject'] = json_decode($criteriaProject->data);
        }

//        //get kpi user
        try{
            $urlUserKpi = 'https://calm-basin-00803.herokuapp.com/api/v1/users/projects/'.$id_user.'?project_id='.$id_project;
            $clientUserKpi = new Client();
            $responseUserKpi = $clientUserKpi->request('GET', $urlUserKpi);
            $kpiUser= json_decode($responseUserKpi->getBody()->getContents());
            if(!$kpiUser){
                $data['kpi'] = $kpiUser->data->project;
            }else{
                $kpiUser = DB::table('user_kpi')->where('id',1)->first();
                $data['kpi'] = json_decode($kpiUser->data, true);
            }
        }catch (\Exception $e){
            $kpiUser = DB::table('user_kpi')->where('id',1)->first();
            $data['kpi'] = json_decode($kpiUser->data, true);
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
            $user = DB::table('user_info')->where('id',1)->first();
            $data['user'] = json_decode($user->data);
        }

        //get kpis user
        try{
            $urlUserProjects = '3.1.20.54/v1/users/'.$id_user.'/projects';
            $clientUserProject = new Client();
            $responseUserProject = $clientUserProject->request('GET', $urlUserProjects);
            $UserProjects = json_decode($responseUserProject->getBody()->getContents());
            $projects = $UserProjects->results;

            // get criteria of project
            if($projects){
                foreach ($projects as $project){
                    $id_project = $project->id;
                    $urlCriteriaUserProject = 'http://206.189.34.124:5000/api/group8/kpis?project_id='.$id_project.'&employee_id='.$id_user;
                    $clientCriteriaUserProject = new Client();
                    $response = $clientCriteriaUserProject->request('GET', $urlCriteriaUserProject);
                    $userCriteria = json_decode($response->getBody()->getContents());
                    $data[$id_project]['criteria'] = $userCriteria->criterias;

                    $urlUserKpi = 'https://calm-basin-00803.herokuapp.com/api/v1/users/projects/'.$id_user.'?project_id='.$id_project;
                    $clientUserKpi = new Client();
                    $response = $clientUserKpi->request('GET', $urlUserKpi);
                    $userKpi = json_decode($response->getBody()->getContents());
                    $data[$id_project]['kpi'] = $userKpi->data->project;
                }
            }else{
                $id_projects = ['1664a77a-9c82-46af-83fe-b05b5e2e4bf8', '22219299-368b-4b62-85c7-fe92b23613ef'];
                foreach ($id_projects as $id_project ){
                    $criteriaProject = DB::table('user_criteria')->where('id',1)->first();
                    $data[$id_project]['criteria'] = json_decode($criteriaProject->data);
                    $kpiUser = DB::table('user_kpi')->where('id',1)->first();
                    $data[$id_project]['kpi'] = json_decode($kpiUser->data, true);
                }
            }
        }catch (\Exception $e){
            $id_projects = ['1664a77a-9c82-46af-83fe-b05b5e2e4bf8', '22219299-368b-4b62-85c7-fe92b23613ef'];
            foreach ($id_projects as $id_project ){
                $criteriaProject = DB::table('user_criteria')->where('id',1)->first();
                $data[$id_project]['criteria'] = json_decode($criteriaProject->data);
                $kpiUser = DB::table('user_kpi')->where('id',1)->first();
                $data[$id_project]['kpi'] = json_decode($kpiUser->data, true);
            }
        }

        return response()->json(['success' => 1, 'data' => $data], 200);
    }
}

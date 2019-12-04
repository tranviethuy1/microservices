<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
class ProjectController extends Controller
{
// ---------------------------------------------------------------------------------------------

    // Calculate KPI project
    public function evaluateKpiProject(Request $request){
        $projectId  = $request->id_project;
        try{
            $data = $this->getDataKpiProject($projectId);
            return response()->json(['success' => 1, 'data' => $data], 200);
        }catch (\Exception $e){
            return response()->json(['error' => ['message' => 'Something was wrong with evaluating kpi of departments']], 200);
        }
    }

    // List kpi of all projects
    public function evaluateKpiAllProject(){
        $data = array();
        try{
            $clientInfo = new Client();
            $apiUrlProjects = "http://3.1.20.54/v1/projects";
            $responseProject = $clientInfo->request('GET', $apiUrlProjects);
            $dataProjects = json_decode($responseProject->getBody()->getContents());
            $projects = $dataProjects->results;
            foreach ($projects as $project){
                if(isset($project->completed_time)){
                    $data[]= $this->getDataKpiProject($project->id);
                }
            }
            return response()->json(['success' => 1, 'data' => $data], 200);
        }catch (\Exception $e){
            return response()->json(['error' => ['message' => 'Something was wrong with evaluating kpi of departments']], 400);
        }
    }

    // List kpi of all projects with year
    public function evaluateKpiAllProjectYear(Request $request){
        $data = array();
        try{
            $year = $request->year;
            $clientInfo = new Client();
            $apiUrlProjects = "http://3.1.20.54/v1/projects";
            $responseProject = $clientInfo->request('GET', $apiUrlProjects);
            $dataProjects = json_decode($responseProject->getBody()->getContents());
            $projects = $dataProjects->results;
            foreach ($projects as $project){
                if(isset($project->completed_time)){
                    if(date('Y',$project->created_time) == $year){
                        $data[]= $this->getDataKpiProject($project->id);
                    }
                }
            }
            if (empty($data)){
                $data = array(
                    'result'=>"Don't have project in ".$year." "
                );
            }
            return response()->json(['success' => 1, 'data' => $data], 200);
        }catch (\Exception $e){
            return response()->json(['error' => ['message' => 'Something was wrong with evaluating kpi of departments']], 400);
        }
    }

    // Get data KPI
    public function getDataKpiProject($idProject){
        // get criterion in project + default 4
        try{
            $clientCriterion = new Client();
            $apiUrlCriterion = "http://206.189.34.124:5000/api/group8/kpis?project_id=".$idProject;
            $responseCriterion = $clientCriterion->request('GET', $apiUrlCriterion);
            $dataCriterion = json_decode($responseCriterion->getBody()->getContents());
            $progressWeight = $dataCriterion->criterias[0]->ratio;
            $qualityWeight = $dataCriterion->criterias[1]->ratio;
            $scaleWeight = $dataCriterion->criterias[2]->ratio;
            $technologyWeight = $dataCriterion->criterias[3]->ratio;
        }catch (\Exception $e){
            return response()->json(['error' => 1, 'message' => 'Something was wrong with api get criteria '], 400);
        }

        // get information project
        try{
            $clientProject = new Client();
            $apiUrlProject = "http://3.1.20.54/v1/projects/".$idProject;
            $responseProject = $clientProject->request('GET', $apiUrlProject);
            $dataProjects = json_decode($responseProject->getBody()->getContents());
            if (isset($dataProjects->name)){
                $nameProject = $dataProjects->name;
            }else{
                $nameProject = '';
            }
            if(isset($dataProjects->completed_time)){
                $timeLate = abs($dataProjects->completed_time - $dataProjects->deadline);
                $timeStandard = abs($dataProjects->deadline - $dataProjects->created_time);
                $createTime = $dataProjects->created_time;
                $completeTime = $dataProjects->completed_time;
                if($timeLate < 0){
                    $projectReality = round(($timeLate/$timeStandard)+1,2);
                }elseif($timeLate ==0){
                    $projectReality = 1;
                }else{
                    $projectReality = round(($timeLate/$timeStandard),2);
                }
                $technologyReality = 0.1*$dataProjects->technique_index;
            }else{
                return response()->json(['error' => 1, 'message' => "Project wasn't finished "], 400);
            }

        }catch (\Exception $e){
            return response()->json(['error' => 1, 'message' => 'Something was wrong with api get project information '], 400);
        }

        try{
            // get information task in project
            $clientTaskProject = new Client();
            $apiUrlTaskProject = "http://3.1.20.54/v1/tasks?project_id=".$idProject;
            $responseTaskProject = $clientTaskProject->request('GET', $apiUrlTaskProject);
            $dataTaskProject = json_decode($responseTaskProject->getBody()->getContents());
            $totalLevel = 0;
            $totalTask = 0;
            $totalTimeExecute = 0;
            $totalTimeStandard = 0;
            $resultTasks = $dataTaskProject->results;
            foreach ($resultTasks as $result){
                if(isset($result->completed_time)){
                    $totalTimeExecute += $result->completed_time - $result->created_time;
                    if(isset($result->deadline)){
                        $totalTimeStandard += $result->deadline - $result->created_time;
                    }else{
                        $totalTimeStandard += $result->completed_time - $result->created_time;
                    }
                }
                $totalLevel += $result->level;
                $totalTask++;
            }
            if($totalTimeExecute == 0 && $totalTimeStandard == 0){
                $qualityReality = 0;
            }else{
                $qualityReality = round($totalTimeStandard/$totalTimeExecute,2);
            }
            if($totalLevel == 0 && $totalTask == 0){
                $scaleReality = 0;
            }else{
                $scaleReality = round($totalLevel/$totalTask,2)*0.1;
            }
        }catch (\Exception $e){
            return response()->json(['error' => 1, 'message' => 'Something was wrong with api get task information in project'], 400);
        }

        // calculate kip  and compare
        $kpiProject = round($projectReality*$progressWeight + $qualityReality*$qualityWeight + $scaleReality*$scaleWeight + $technologyReality*$technologyWeight,2);
        $kpiStandard = round($progressWeight + $qualityWeight + $scaleReality*$scaleWeight + $technologyReality*$technologyWeight,2);

        $status = '';

        if($kpiProject > $kpiStandard){
            $status = 'Very Good ';
        }elseif($kpiProject == $kpiStandard){
            $status = 'Good';
        }else{
            $status = 'Bad';
        }

        $data = array(
            'id_project'=>$idProject,
            'name'=>$nameProject,
            'kpi'=>$kpiProject,
            'kpi_standard'=>$kpiStandard,
            'status'=>$status,
            'created_time'=>date("Y-m-d H:i:s", $createTime),
            'complete_time'=>date("Y-m-d H:i:s", $completeTime)
        );
        return $data;
    }
}

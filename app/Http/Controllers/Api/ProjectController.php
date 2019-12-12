<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use App\KpiUpdate;
use Illuminate\Support\Facades\DB;
class ProjectController extends Controller
{
// ---------------------------------------------------------------------------------------------

    // Calculate KPI project
    public function evaluateKpiProject(Request $request){
        $projectId  = $request->id_project;
        // get information from database when error connect project fail
        $flag = false;
        $kpiProjects = DB::table('kpi_fake_tables')->get();
        foreach ($kpiProjects as $kpiProject){
            if($projectId == $kpiProject->project_id){
                $flag = true;
            }
        }
        if($flag == true){
            $kpiProject = DB::table('kpi_fake_tables')->where('project_id',$projectId)->first();
            $data[]= array(
                'id_project'=>$kpiProject->project_id,
                'id_criteria'=>$kpiProject->criteria_id,
                'name'=>$kpiProject->name,
                'kpi'=>$kpiProject->kpi,
                'kpi_standard'=>$kpiProject->kpi_standard,
                'reality'=>json_decode($kpiProject->reality),
                'status'=>$kpiProject->status,
                'created_time'=>$kpiProject->created_time,
                'complete_time'=>$kpiProject->complete_time
            );
            return response()->json(['success' => 1, 'data' => $data], 200);
        }

        // get information kpi when connect success
        try{
            $data = $this->getDataKpiProject($projectId);
            return response()->json(['success' => 1, 'data' => $data], 200);
        }catch (\Exception $e){
            return response()->json(['error' => ['message' => 'Something was wrong with evaluating kpi of project']], 200);
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
                if($project->completed_time !== null){
                    $data[]= $this->getDataKpiProject($project->id);
                }
            }
            return response()->json(['success' => 1, 'data' => $data], 200);
        }catch (\Exception $e){
            // get data from database when error connect project
            $allKpiProjects = DB::table('kpi_fake_tables')->get();
            foreach ($allKpiProjects as $kpiProject){
                $data[]= array(
                    'id_project'=>$kpiProject->project_id,
                    'id_criteria'=>$kpiProject->criteria_id,
                    'name'=>$kpiProject->name,
                    'kpi'=>$kpiProject->kpi,
                    'kpi_standard'=>$kpiProject->kpi_standard,
                    'reality'=>json_decode($kpiProject->reality),
                    'status'=>$kpiProject->status,
                    'created_time'=>$kpiProject->created_time,
                    'complete_time'=>$kpiProject->complete_time
                );
            }
//            return response()->json(['error' => ['message' => 'Something was wrong with evaluating kpi of project']], 400);
        }
        return response()->json(['success' => 1, 'data' => $data], 200);
    }

    // Get project KPI max
    public function evaluateKpiMaxProject(){
        $data = array();
        try{
            $clientInfo = new Client();
            $apiUrlProjects = "http://3.1.20.54/v1/projects";
            $responseProject = $clientInfo->request('GET', $apiUrlProjects);
            $dataProjects = json_decode($responseProject->getBody()->getContents());
            $projects = $dataProjects->results;
            $kpiMax = 0;
            foreach ($projects as $project){
                if($project->completed_time !== null){
                    if($this->getDataKpiProject($project->id)['kpi'] >= $kpiMax){
                        if($this->getDataKpiProject($project->id)['kpi'] > $kpiMax){
                            $data = array();
                        }
                        $kpiMax = $this->getDataKpiProject($project->id)['kpi'];
                        $data[] = $this->getDataKpiProject($project->id);
                    }
                }
            }
        }catch (\Exception $e){
            $kpiMax = 0;
            $kpiProjects = DB::table('kpi_fake_tables')->get();
            foreach ($kpiProjects as $kpiProject){
                $kpiProject = (array)$kpiProject;
                if($kpiProject['complete_time'] !== null){
                    if((float)$kpiProject['kpi']>=$kpiMax){
                        if((float)$kpiProject['kpi'] > $kpiMax){
                            $data = array();
                        }
                        $kpiMax = $kpiProject['kpi'];
                        $data[] = array(
                            'id'=>$kpiProject['id'],
                            'project_id'=>$kpiProject['project_id'],
                            'criteria_id'=>$kpiProject['criteria_id'],
                            'name'=>$kpiProject['name'],
                            'kpi'=>$kpiProject['kpi'],
                            'kpi_standard'=>$kpiProject['kpi_standard'],
                            'reality'=>json_decode($kpiProject['reality']),
                            'status'=>$kpiProject['status'],
                            'created_time'=>$kpiProject['created_time'],
                            'complete_time'=>$kpiProject['complete_time'],
                        );
                    }
                }
            }
            // return response()->json(['error' => ['message' => 'Something was wrong with evaluating kpi of departments']], 400);
        }
        return response()->json(['success' => 1, 'data' => $data], 200);
    }

    // Get project KPI min
    public function evaluateKpiMinProject(){
        $data = array();
        try{
            $clientInfo = new Client();
            $apiUrlProjects = "http://3.1.20.54/v1/projects";
            $responseProject = $clientInfo->request('GET', $apiUrlProjects);
            $dataProjects = json_decode($responseProject->getBody()->getContents());
            $projects = $dataProjects->results;
            // get project complete
            $projectsKPI = array();
            foreach ($projects as $project){
                if(isset($project->completed_time)){
                    $projectsKPI[] = $project;
                }
            }
            // get all kpi project kpi min
            $kpiMin = $this->getDataKpiProject($projectsKPI[0]->id)['kpi'];
            for ($i =0 ;$i<count($projectsKPI);$i++){
                if($this->getDataKpiProject($projectsKPI[$i]->id)['kpi'] <= $kpiMin){
                    if($this->getDataKpiProject($projectsKPI[$i]->id)['kpi'] < $kpiMin){
                        $data = array();
                    }
                    $kpiMin = $this->getDataKpiProject($projectsKPI[$i]->id)['kpi'];
                    $data[] = $this->getDataKpiProject($projectsKPI[$i]->id);
                }
            }
        }catch (\Exception $e){
            $kpiProjects = DB::table('kpi_fake_tables')->get();
            $projectsKPI = array();
            foreach ($kpiProjects as $kpiProject){
                if($kpiProject->complete_time !== null){
                    $projectsKPI[] = (array)$kpiProject;
                }
            }

            $kpiMin = $projectsKPI[0]['kpi'];
            for ($i =0 ;$i<count($projectsKPI);$i++){
                if($projectsKPI[$i]['kpi'] <= $kpiMin){
                    if($projectsKPI[$i]['kpi'] < $kpiMin){
                        $data = array();
                    }
                    $kpiMin = $projectsKPI[$i]['kpi'];
                    $data[] = array(
                        'id'=>$projectsKPI[$i]['id'],
                        'project_id'=>$projectsKPI[$i]['project_id'],
                        'criteria_id'=>$projectsKPI[$i]['criteria_id'],
                        'name'=>$projectsKPI[$i]['name'],
                        'kpi'=>$projectsKPI[$i]['kpi'],
                        'kpi_standard'=>$projectsKPI[$i]['kpi_standard'],
                        'reality'=>json_decode($projectsKPI[$i]['reality']),
                        'status'=>$projectsKPI[$i]['status'],
                        'created_time'=>$projectsKPI[$i]['created_time'],
                        'complete_time'=>$projectsKPI[$i]['complete_time'],
                    );
                }
            }
            // return response()->json(['error' => ['message' => 'Something was wrong with evaluating kpi of departments']], 400);
        }
        return response()->json(['success' => 1, 'data' => $data], 200);
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
                if($project->completed_time !== null){
                    if(date('Y',$project->created_time) == $year){
                        $data[]= $this->getDataKpiProject($project->id);
                    }
                }
            }
        }catch (\Exception $e){
            $kpiProjects = DB::table('kpi_fake_tables')->get();
            foreach ($kpiProjects as $kpiProject){
                $kpiProject = (array)$kpiProject;
                if($kpiProject['complete_time'] !== null){
                    if(date('Y',strtotime($kpiProject['created_time'])) == $year){
                        $data[]= array(
                            'id'=>$kpiProject['id'],
                            'project_id'=>$kpiProject['project_id'],
                            'criteria_id'=>$kpiProject['criteria_id'],
                            'name'=>$kpiProject['name'],
                            'kpi'=>$kpiProject['kpi'],
                            'kpi_standard'=>$kpiProject['kpi_standard'],
                            'reality'=>json_decode($kpiProject['reality']),
                            'status'=>$kpiProject['status'],
                            'created_time'=>$kpiProject['created_time'],
                            'complete_time'=>$kpiProject['complete_time'],
                        );
                    }
                }
            }
            // return response()->json(['error' => ['message' => 'Something was wrong with evaluating kpi of project']], 400);
        }

        if (empty($data)){
            $data = array(
                'result'=>"Don't have project in ".$year." "
            );
        }
        return response()->json(['success' => 1, 'data' => $data], 200);
    }

    // Get data KPI
    public function getDataKpiProject($idProject){

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
                if($timeLate <= 0){
                    $projectReality = 1;
                }else{
                    $projectReality = round(($timeLate/$timeStandard),2);
                }
                $technologyReality = round(0.1*$dataProjects->technique_index,2);
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
                if($totalTimeExecute <= $totalTimeStandard){
                    $qualityReality = 1;
                }else{
                    $qualityReality = round($totalTimeStandard/$totalTimeExecute,2);
                }
            }
            if($totalLevel == 0 && $totalTask == 0){
                $scaleReality = 0;
            }else{
                $scaleReality = round(($totalLevel/$totalTask)*0.1,2);
            }
        }catch (\Exception $e){
            return response()->json(['error' => 1, 'message' => 'Something was wrong with api get task information in project'], 400);
        }

        // get criterion in project + default 4
        try{
            $clientCriterion = new Client();
            $apiUrlCriterion = "http://206.189.34.124:5000/api/group8/kpis?project_id=".$idProject;
            $responseCriterion = $clientCriterion->request('GET', $apiUrlCriterion);
            $dataCriterion = json_decode($responseCriterion->getBody()->getContents());
            $criterionInfos= $dataCriterion->criterias;
            $criteriaId = $dataCriterion->id;
            //
            $addCriteria = array();
            $progressWeight = 0;
            $qualityWeight = 0;
            $scaleWeight = 0;
            $technologyWeight = 0;
            $jsonDetailInfo = array();
            foreach ($criterionInfos as $criterionInfo){
                switch ($criterionInfo->name) {
                    case 'Tiến độ dự án':
                        $progressWeight = $criterionInfo->ratio;
                        $jsonDetailInfo[]=array(
                            'data' =>$projectReality,
                            'ratio' =>$criterionInfo->ratio,
                            'note'=>$criterionInfo->note,
                            'name'=>$criterionInfo->name
                        );
                        break;
                    case 'Chất lượng dự án':
                        $qualityWeight = $criterionInfo->ratio;
                        $jsonDetailInfo[]=array(
                            'data' =>$qualityReality,
                            'ratio' =>$criterionInfo->ratio,
                            'note'=>$criterionInfo->note,
                            'name'=>$criterionInfo->name
                        );
                        break;
                    case 'Quy mô mức độ dự án':
                        $scaleWeight = $criterionInfo->ratio;
                        $jsonDetailInfo[]=array(
                            'data' =>$scaleReality,
                            'ratio' =>$criterionInfo->ratio,
                            'note'=>$criterionInfo->note,
                            'name'=>$criterionInfo->name
                        );
                        break;
                    case 'Yếu tố kĩ thuật':
                        $technologyWeight = $criterionInfo->ratio;
                        $jsonDetailInfo[]=array(
                            'data' =>$technologyReality,
                            'ratio' =>$criterionInfo->ratio,
                            'note'=>$criterionInfo->note,
                            'name'=>$criterionInfo->name
                        );
                        break;
                    default:
                        // check in database to get value data to get value which updated
                        $kpiUpdateProject = DB::table("kpi_project_update")->where('id_project',$idProject)->where('id_criteria',$criteriaId)->first();
                        if($kpiUpdateProject !== null){
                            $dataCriteriasSaved = json_decode($kpiUpdateProject->data);
                            foreach ($dataCriteriasSaved as $dataCriteriaSaved){
                                if ($dataCriteriaSaved->name == $criterionInfo->name){
                                    $addCriteria[$criterionInfo->name]= array('ratio'=>$criterionInfo->ratio,'data'=>$dataCriteriaSaved->reality);
                                    $jsonDetailInfo[]=array(
                                        'data' =>$dataCriteriaSaved->reality,
                                        'ratio' =>$criterionInfo->ratio,
                                        'note'=>$criterionInfo->note,
                                        'name'=>$criterionInfo->name
                                    );
                                }
                            }
                        }else{
                            $addCriteria[$criterionInfo->name]= array('ratio'=>$criterionInfo->ratio,'data'=>0);
                            $jsonDetailInfo[]=array(
                                'data' =>0,
                                'ratio' =>$criterionInfo->ratio,
                                'note'=>$criterionInfo->note,
                                'name'=>$criterionInfo->name
                            );
                        }
                        break;
                }
            }
        }catch (\Exception $e){
            return response()->json(['error' => 1, 'message' => 'Something was wrong with api get criteria '], 400);
        }

        // calculate kip  and compare
        $addCriteriaValue = 0;
        foreach ($addCriteria as $key => $value){
            $addCriteriaValue += $value['ratio']*$value['data'];
        }
        $kpiProject = round($projectReality*$progressWeight + $qualityReality*$qualityWeight + $scaleReality*$scaleWeight + $technologyReality*$technologyWeight +$addCriteriaValue,2);
        $kpiStandard = round($progressWeight + $qualityWeight + $scaleReality*$scaleWeight + $technologyReality*$technologyWeight+$addCriteriaValue,2);

        if($kpiProject == $kpiStandard){
            $status = 'Good';
        }else{
            $status = 'Bad';
        }

        $result = array(
            'id_project'=>$idProject,
            'id_criteria'=>$criteriaId,
            'name'=>$nameProject,
            'kpi'=>$kpiProject,
            'kpi_standard'=>$kpiStandard,
            'reality'=>$jsonDetailInfo,
            'status'=>$status,
            'created_time'=>date("Y-m-d H:i:s", $createTime),
            'complete_time'=>date("Y-m-d H:i:s", $completeTime)
        );
        return $result;
    }

    // Update value of KPI project when click button
    public function updateValueKpiProject(Request $request){
        try {
            $kpiUpdate = new KpiUpdate();
            $requestBody = request()->json()->all();
            $projectId = $requestBody['id_project'];
            // check project ID
            $criteriaId = $requestBody['id_criteria'];
            $data = $requestBody['result'];
            // get new kpi project
            $newKpiProject = 0;
            foreach ($data as $value){
                $newKpiProject += $value['reality']*$value['ratio'];
            }

            // check product id in database
            $flag = false;
            $kpiProjects = DB::table('kpi_fake_tables')->get();
            foreach ($kpiProjects as $kpiProject){
                if($projectId == $kpiProject->project_id){
                    $flag = true;
                }
            }
            if($flag){
                // update reality to database
                $kpiProject = DB::table('kpi_fake_tables')
                    ->where('project_id',$projectId)->where('criteria_id',$criteriaId)->first();
                $oldKpiProject = $kpiProject->kpi;
                $oldKpiStandProject = $kpiProject->kpi_standard;
                if((float)$oldKpiProject > (float)$newKpiProject){
                    $newKpiStandard = $oldKpiStandProject - ((float)$oldKpiProject -(float)$newKpiProject );
                }else{
                    $newKpiStandard = $oldKpiStandProject + ((float)$newKpiProject - (float)$oldKpiProject);
                }

                DB::table('kpi_fake_tables')->where('project_id',$projectId)
                    ->update(['reality' => json_encode($data),'kpi'=>round($newKpiProject,2),'kpi_standard'=>round($newKpiStandard,2)]);
            }else{
                // check data of project
                $kpiProject = DB::table('kpi_project_update')
                    ->where('id_project',$projectId)->where('id_criteria',$criteriaId)->first();
                if(isset($kpiProject)){
                    DB::table('kpi_project_update')
                        ->where('id_project',$projectId)->where('id_criteria',$criteriaId)->delete();
                }
                // old kpi project

                // Save updated data to database
                $kpiUpdate->id_project = $projectId;
                $kpiUpdate->id_criteria = $criteriaId;
                $kpiUpdate->data = json_encode($data);
                $kpiUpdate->save();
            }
            return response()->json(['success' => 1, 'newKpiProject' => round($newKpiProject,2)], 200);
        }catch (\Exception $exception){
            return response()->json(['error' => 1, 'message' => 'Something was wrong with save database '], 400);
        }
    }
}

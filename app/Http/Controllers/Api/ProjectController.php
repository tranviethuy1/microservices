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
                'standard'=>json_decode($kpiProject->standard),
                'status'=>$kpiProject->status,
                'created_time'=>$kpiProject->created_time,
                'complete_time'=>$kpiProject->complete_time
            );
            return response()->json(['success' => 1, 'data' => $data], 200);
        }

        // get information kpi when connect success
        if($this->checkConnectAllApi()){
            if($this->checkIdProjectCriteria($projectId)){
                $data = $this->getDataKpiProject($projectId);
            }else{
                return response()->json(['error' => ['message' => "Project don't have criteria to get value "]], 200);
            }
            return response()->json(['success' => 1, 'data' => $data], 200);
        }else{
            return response()->json(['error' => ['message' => 'Error connect other api']], 200);
        }
    }

    // List kpi of all projects
    public function evaluateKpiAllProject(){
        $data = array();
        if($this->checkConnectAllApi()){
            $dataProjects = $this->getContentApiAllProject();
            $projects = $dataProjects->results;
            foreach ($projects as $project){
                if($project->completed_time !== null){
                    if($this->checkIdProjectCriteria($project->id)){
                        $data[]= $this->getDataKpiProject($project->id);
                    }
                }
            }
            return response()->json(['success' => 1, 'data' => $data], 200);
        }else{
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
                    'standard'=>json_decode($kpiProject->standard),
                    'status'=>$kpiProject->status,
                    'created_time'=>$kpiProject->created_time,
                    'complete_time'=>$kpiProject->complete_time
                );
            }
        }
        return response()->json(['success' => 1, 'data' => $data], 200);
    }

    // Get project KPI max
    public function evaluateKpiMaxProject(){
        $data = array();
        if($this->checkConnectAllApi()){
            $dataProjects = $this->getContentApiAllProject();
            $projects = $dataProjects->results;
            $kpiMax = 0;
            foreach ($projects as $project){
                if($project->completed_time !== null){
                    if($this->checkIdProjectCriteria($project->id)){
                        if($this->getDataKpiProject($project->id)['kpi'] >= $kpiMax){
                            if($this->getDataKpiProject($project->id)['kpi'] > $kpiMax){
                                $data = array();
                            }
                            $kpiMax = $this->getDataKpiProject($project->id)['kpi'];
                            $data[] = $this->getDataKpiProject($project->id);
                        }
                    }
                }
            }
        }else{
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
                            'standard'=>json_decode($kpiProject['standard']),
                            'status'=>$kpiProject['status'],
                            'created_time'=>$kpiProject['created_time'],
                            'complete_time'=>$kpiProject['complete_time'],
                        );
                    }
                }
            }
        }
        return response()->json(['success' => 1, 'data' => $data], 200);
    }

    // Get project KPI min
    public function evaluateKpiMinProject(){
        $data = array();
        if($this->getContentApiAllProject()){
            $dataProjects = $this->getContentApiAllProject();
            $projects = $dataProjects->results;
            // get project complete
            $projectsKPI = array();
            foreach ($projects as $project){
                if(isset($project->completed_time)){
                    if($this->checkIdProjectCriteria($project->id)){
                        $projectsKPI[] = $project;
                    }
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
        }else{
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
                        'standard'=>json_decode($projectsKPI[$i]['standard']),
                        'status'=>$projectsKPI[$i]['status'],
                        'created_time'=>$projectsKPI[$i]['created_time'],
                        'complete_time'=>$projectsKPI[$i]['complete_time'],
                    );
                }
            }
        }
        return response()->json(['success' => 1, 'data' => $data], 200);
    }




    // List kpi of all projects with year
    public function evaluateKpiAllProjectYear(Request $request){
        $data = array();
        $year = $request->year;
        if($this->checkConnectAllApi()){
            $dataProjects = $this->getContentApiAllProject();
            $projects = $dataProjects->results;
            foreach ($projects as $project){
                if($project->completed_time !== null){
                    if(date('Y',$project->created_time) == $year){
                        if($this->checkIdProjectCriteria($project->id)){
                            $data[]= $this->getDataKpiProject($project->id);
                        }
                    }
                }
            }
        }else{
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
                            'standard'=>json_decode($kpiProject['standard']),
                            'status'=>$kpiProject['status'],
                            'created_time'=>$kpiProject['created_time'],
                            'complete_time'=>$kpiProject['complete_time'],
                        );
                    }
                }
            }
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
        $dataProjects = $this->getContentApiProject($idProject);
        if (isset($dataProjects->name)){
            $nameProject = $dataProjects->name;
        }else{
            $nameProject = '';
        }
        if($dataProjects->completed_time !== null){
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
        }

        // get information task in project
        $dataTaskProject = $this->getContentApiProjectTask($idProject);
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

        // get criterion in project + default 4
        $dataCriterion = $this->getContentCriteriaProject($idProject);
        $criteriaId = $dataCriterion->id;
        $criterionInfos= $dataCriterion->criterias;
        // check criteria in database different when call if different delete data in database -> need update
        $isDeleteDatabase = false;
        $getNameCriteriaApi = array();
        foreach ($criterionInfos as $criterionInfo){
            array_push($getNameCriteriaApi,$criterionInfo->name);
        }
        $defaultNameCriteriaSavedDb = array();
        $criteriaInfoDb = DB::table('kpi_project_update')->where('id_project',$idProject)->first();
        if($criteriaInfoDb !== null){
            $criteriaDbs = json_decode($criteriaInfoDb->data);
            foreach ($criteriaDbs as $criteriaDb){
                array_push($defaultNameCriteriaSavedDb,$criteriaDb->name);
            }
        }

        $compareArrayDb1 = array_diff($getNameCriteriaApi,$defaultNameCriteriaSavedDb);
        $compareArrayDb2 = array_diff($defaultNameCriteriaSavedDb,$getNameCriteriaApi);
        if(count($criterionInfos) == count($defaultNameCriteriaSavedDb)){
            if (!(empty($compareArrayDb1) && empty($compareArrayDb2))){
                $isDeleteDatabase = true;
            }
        }else{
            $isDeleteDatabase = true;
        }

        if($isDeleteDatabase){
            DB::table('kpi_project_update')->where('id_project',$idProject)->delete();
        }

        // Get reality value
        $addCriteria = array();
        $progressWeight = 0;
        $qualityWeight = 0;
        $scaleWeight = 0;
        $technologyWeight = 0;
        $jsonDetailInfo = array();
        $jsonStandard = array();
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
                    $jsonStandard[]= array(
                        'data' =>1,
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
                    $jsonStandard[]= array(
                        'data' =>1,
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
                    $jsonStandard[]= array(
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
                    $jsonStandard[]=array(
                        'data' =>$technologyReality,
                        'ratio' =>$criterionInfo->ratio,
                        'note'=>$criterionInfo->note,
                        'name'=>$criterionInfo->name
                    );
                    break;
                default:
                    // check in database to get value data to get value which updated
                    if(!$isDeleteDatabase){
                        $kpiUpdateProject = DB::table("kpi_project_update")->where('id_project',$idProject)->where('id_criteria',$criteriaId)->first();
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
                                $jsonStandard[]=array(
                                    'data' =>0.5,
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
                        $jsonStandard[]=array(
                            'data' =>0.5,
                            'ratio' =>$criterionInfo->ratio,
                            'note'=>$criterionInfo->note,
                            'name'=>$criterionInfo->name
                        );
                    }
                    break;
            }
        }

        // calculate kip  and compare
        $addCriteriaValue = 0;
        $countAddCriteria = 0;
        foreach ($addCriteria as $key => $value){
            $countAddCriteria += $value['ratio'];
            $addCriteriaValue += $value['ratio']*$value['data'];
        }
        $kpiProject = round($projectReality*$progressWeight + $qualityReality*$qualityWeight + $scaleReality*$scaleWeight + $technologyReality*$technologyWeight +$addCriteriaValue,2);
        $kpiStandard = round($progressWeight + $qualityWeight + $scaleReality*$scaleWeight + $technologyReality*$technologyWeight+$countAddCriteria*0.5,2);

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
            'standard'=>$jsonStandard,
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
//                $kpiProject = DB::table('kpi_fake_tables')
//                    ->where('project_id',$projectId)->where('criteria_id',$criteriaId)->first();
//                $oldKpiProject = $kpiProject->kpi;
//                $oldKpiStandProject = $kpiProject->kpi_standard;
//                if((float)$oldKpiProject > (float)$newKpiProject){
//                    $newKpiStandard = $oldKpiStandProject - ((float)$oldKpiProject -(float)$newKpiProject );
//                }else{
//                    $newKpiStandard = $oldKpiStandProject + ((float)$newKpiProject - (float)$oldKpiProject);
//                }

                DB::table('kpi_fake_tables')->where('project_id',$projectId)
                    ->update(['reality' => json_encode($data),'kpi'=>round($newKpiProject,2)]);
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


    // Get infor api connect
    public function getContentApiAllProject(){
        $clientInfo = new Client();
        $apiUrlProjects = "http://3.1.20.54/v1/projects";
        $responseProject = $clientInfo->request('GET', $apiUrlProjects);
        return json_decode($responseProject->getBody()->getContents());
    }

    public function getContentApiProject($idProject){
        $clientProject = new Client();
        $apiUrlProject = "http://3.1.20.54/v1/projects/".$idProject;
        $responseProject = $clientProject->request('GET', $apiUrlProject);
        return json_decode($responseProject->getBody()->getContents());
    }

    public function getContentApiProjectTask($idProject){
        $clientTaskProject = new Client();
        $apiUrlTaskProject = "http://3.1.20.54/v1/tasks?project_id=".$idProject;
        $responseTaskProject = $clientTaskProject->request('GET', $apiUrlTaskProject);
        return json_decode($responseTaskProject->getBody()->getContents());
    }

    public function getContentCriteriaProject($idProject){
        $clientCriterion = new Client();
        $apiUrlCriterion = "http://206.189.34.124:5000/api/group8/kpis?project_id=".$idProject;
        $responseCriterion = $clientCriterion->request('GET', $apiUrlCriterion);
        return json_decode($responseCriterion->getBody()->getContents());
    }

    public function getContentCriteriaAllProject(){
        $clientCriterion = new Client();
        $apiUrlAllCriterion = "http://206.189.34.124:5000/api/group8/kpis/all?type=PROJECT";
        $responseAllCriterion = $clientCriterion->request('GET', $apiUrlAllCriterion);
        return json_decode($responseAllCriterion->getBody()->getContents());
    }

    // get all id_project which created criteria

    public function getIdsProjectCriteria(){
        $ids = array();
        $criteriaProjects = $this->getContentCriteriaAllProject()->kpis;
        foreach ($criteriaProjects as $criteriaProject){
            array_push($ids,$criteriaProject->project_id);
        }
        return $ids;
    }

    // check project which have criteria information
    public function checkIdProjectCriteria($projectId){
        $idProjectCriteria = $this->getIdsProjectCriteria();
        if(in_array($projectId,$idProjectCriteria)){
            return true;
        }
        return false;
    }
    // check connect api which use to calculate kpi project
    public function checkConnectAllApi(){
        $check = true;
        try {
            $dataAllCriteriaProject = $this->getContentCriteriaAllProject();
            $idProjectCriteria = $this->getIdsProjectCriteria();
            $projects = $this->getContentApiAllProject()->results;
            foreach ($projects as $project){
                if($project->completed_time !== null){
                    if(in_array($project->id,$idProjectCriteria)){
                        $dataTaskProject = $this->getContentApiProjectTask($project->id);
                        $dataCriterion = $this->getContentCriteriaProject($project->id);
                    }
                }
            }
        }catch (\Exception $exception){
            $check = false;
        }
        return true;
    }

}

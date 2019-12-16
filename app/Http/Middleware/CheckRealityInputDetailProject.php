<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Api\ProjectController;
use Closure;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use function GuzzleHttp\Psr7\str;

class CheckRealityInputDetailProject
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $requestBody = request()->json()->all();
        $dataSaved = $requestBody['result'];
        $formatInput = array(
            'error'=>'Something input is wrong ',
            'format_correct'=>array(
                'id_project'=>'',
                'id_criteria'=>'',
                'result'=>[
                    [
                        'reality'=>'',
                        'ratio'=>'',
                        'name'=>''
                    ],
                    [
                        'reality'=>'',
                        'ratio'=>'',
                        'name'=>''
                    ]
                ]
            )
        );
        // check format input
        foreach ($dataSaved as $value){
            if(!isset($value['reality']) || !isset($value['ratio']) || !isset($value['name'])){
                return response()->json(['error' => 1, 'message' => $formatInput],400);
                break;
            }
        }

        if(!isset($requestBody['id_project'])){
            // check empty project id
            return response()->json(['error' => 1, 'message' => 'You must have project id in json when update'],400);
        }else {
            $idProject = $requestBody['id_project'];

            // get data of criteria updated
            $inputNameCriteria = array();
            foreach ($dataSaved as $update) {
                array_push($inputNameCriteria, $update['name']);
            }

            // validate input id criteria
            if (!isset($requestBody['id_criteria'])) {
                return response()->json(['error' => 1, 'message' => 'You must have criteria id in json when update'], 400);
            } else {
                $idCriteria = $requestBody['id_criteria'];
            }

            $totalRatioCriteria = 0;
            foreach ($dataSaved as $value) {
                // check value of reality  in between 0 1
                if ($value['reality'] < 0 || $value['reality'] > 1) {
                    return response()->json(['error' => 1, 'message' => 'Reality data of criteria between 0 to 1'], 400);
                }
                // check value of ratio input
                if ($value['ratio'] < 0 || $value['ratio'] > 1) {
                    return response()->json(['error' => 1, 'message' => 'Ratio data of criteria between 0 to 1'], 400);
                }
                $totalRatioCriteria += $value['ratio'];
            }
            // total ratios of the criteria
            if ((string)$totalRatioCriteria !== '1') {
                return response()->json(['error' => 1, 'message' => 'Total value ratios of criteria must 1 '], 400);
            }

            $projectController = new ProjectController();
            if ($projectController->checkConnectAllApi()) {
                // check value of project
                $flag = false;
                $clientInfo = new Client();
                $apiUrlProjects = "http://3.1.20.54/v1/projects";
                $responseProject = $clientInfo->request('GET', $apiUrlProjects);
                $dataProjects = json_decode($responseProject->getBody()->getContents());
                $projects = $dataProjects->results;
                foreach ($projects as $project) {
                    if (isset($project->completed_time)) {
                        if ($project->id == $idProject) {
                            $flag = true;
                        }
                    }
                }
                if (!$flag) {
                    return response()->json(['error' => 1, 'message' => 'Value project id is wrong '], 400);
                }


                $clientCriterion = new Client();
                $apiUrlCriterion = "http://206.189.34.124:5000/api/group8/kpis?project_id=" . $idProject;
                $responseCriterion = $clientCriterion->request('GET', $apiUrlCriterion);
                $dataCriterion = json_decode($responseCriterion->getBody()->getContents());
                $criterionInfos = $dataCriterion->criterias;
                if ($dataCriterion->id !== $idCriteria) {
                    return response()->json(['error' => 1, 'message' => 'criteria_id is wrong when input '], 400);
                }
                // check criteria
                $defaultNameCriteria = array();
                foreach ($criterionInfos as $criterionInfo) {
                    array_push($defaultNameCriteria, $criterionInfo->name);
                }
                // compare 2 array
                $compareArray1 = array_diff($inputNameCriteria, $defaultNameCriteria);
                $compareArray2 = array_diff($defaultNameCriteria, $inputNameCriteria);
                if (count($defaultNameCriteria) == count($inputNameCriteria)) {
                    if (!(empty($compareArray1) && empty($compareArray2))) {
                        return response()->json(['error' => 1, 'message' => 'Input data is wrong so criteria is different '], 400);
                    }
                } else {
                    return response()->json(['error' => 1, 'message' => 'Input data is wrong so criteria is different '], 400);
                }
            } else {
                // check idProject in database
                $temp = false;
                $idCriteriaDb = 0;
                $kpiProjects = DB::table('kpi_fake_tables')->get();
                foreach ($kpiProjects as $kpiProject) {
                    if ($idProject == $kpiProject->project_id) {
                        $temp = true;
                        $idCriteriaDb = $kpiProject->criteria_id;
                    }
                }
                if (!$temp) {
                    return response()->json(['error' => 1, 'message' => 'Id project is wrong'], 400);
                }
                // check id criteria
                if ((int)$idCriteriaDb !== (int)$idCriteria) {
                    return response()->json(['error' => 1, 'message' => 'Id criteria is wrong'], 400);
                }

                // detail criteria
                $criteriaInfoDb = DB::table('project_criteria')->where('criteria_id', $idCriteriaDb)->first();
                $criteriaDbs = json_decode($criteriaInfoDb->data);

                // check criteria
                $defaultNameCriteriaDb = array();
                foreach ($criteriaDbs as $criteriaDb) {
                    array_push($defaultNameCriteriaDb, $criteriaDb->name);
                }

                // compare 2 array
                $compareArrayDb1 = array_diff($inputNameCriteria, $defaultNameCriteriaDb);
                $compareArrayDb2 = array_diff($defaultNameCriteriaDb, $inputNameCriteria);
                if (count($defaultNameCriteriaDb) == count($inputNameCriteria)) {
                    if (!(empty($compareArrayDb1) && empty($compareArrayDb2))) {
                        return response()->json(['error' => 1, 'message' => 'Input data is wrong so criteria is different '], 400);
                    }
                } else {
                    return response()->json(['error' => 1, 'message' => 'Input data is wrong so criteria is different '], 400);
                }
            }
        }

        return $next($request);
    }
}

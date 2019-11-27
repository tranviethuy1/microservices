<?php

namespace App\Http\Middleware;

use Closure;
use GuzzleHttp\Client;

class CheckInputKpiProject
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
        $projectId = $request->id_project;
        if(empty($projectId)){
            return response()->json(['error' => 'IdProject is required'], 400);
        }
        // Check request in api
        $clientProject = new Client();
        $apiUrlProject = "http://3.1.20.54/v1/projects";
        $responseProject = $clientProject->request('GET', $apiUrlProject);
        $dataProjects = json_decode($responseProject->getBody()->getContents());
        $projects = $dataProjects->results;
        $idProjects = array();
        foreach ($projects as $project){
            if(isset($project->completed_time)){
                array_push($idProjects,$project->id);
            }
        }
        if(!in_array($projectId,$idProjects)){
            return response()->json(['error' => 'IdProject is not correct'], 400);
        }
        return $next($request);
    }
}

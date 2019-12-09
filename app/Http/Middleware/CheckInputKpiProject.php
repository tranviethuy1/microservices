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
        $token = $request->token;

        if(!isset($token) || empty($token)){
            return response()->json(['error' => 'You do not have the role'], 401 );
        }else{
            $role = \App\Role::where('id_employee', $token)->first();
            if(!$role){
                return response()->json(['error' => 'You do not have the role'], 401 );
            }
        }

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
            return response()->json(['error' => 'You can input id_project which have complete_time '], 400);
        }
        return $next($request);
    }
}

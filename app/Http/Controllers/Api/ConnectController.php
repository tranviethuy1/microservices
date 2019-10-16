<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class ConnectController extends Controller
{
    public function connect(){
        return response()->json(["message" => "connect"], 200);
    }
}
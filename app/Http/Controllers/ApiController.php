<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    /**
     * @OA\PathItem(path="/api/v1")
     *
     * @OA\Info(
     *      version="0.0.0",
     *      title="Anophel API Documentation"
     *  )
     */
       
     public function index()
     {   
         
         return response()->json(['success' => true,'errors'=>null,'data'=>'api'], 200);
 
 
     }
 
}

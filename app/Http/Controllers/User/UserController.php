<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Mail\Verify;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use openApi\Annotations as OA;
class UserController extends Controller
{

      /**
        * @OA\Post(
        * path="/api/register",
        * operationId="Register",
        * tags={"Register"},
        * summary="User Register",
        * description="User Register here",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"name","email", "password",},
        *               @OA\Property(property="name", type="text"),
        *               @OA\Property(property="email", type="text"),
        *               @OA\Property(property="password", type="password"),
        *            ),
        *        ),
        *    ),
        *      @OA\Response(
        *          response=201,
        *          description="Register Successfully",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=200,
        *          description="Register Successfully",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=422,
        *          description="Unprocessable Entity",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(response=400, description="Bad request"),
        *      @OA\Response(response=404, description="Resource Not Found"),
        * )
        */
    public function register(Request $request){
        try {
            $validated = $request->validate([
                'name'=> 'required',
                'email' => 'required|unique:users,email',
                'password'=> 'required',

          ]);
          } catch (ValidationException $e) {
              // Validation failed, return a JSON response with validation errors
              return response()->json(['errors' => $e->validator->errors(),'data'=>null], 422);
          }
        $user=User::Where('email',$request->email)->first();
        if($user){
            return response()->json(['success' => false, 'errors' => 'user_exist','data'=>$user], 200);

        }
    
        $user=User::create([
        
            'name'=>$request['name'],
            'email'=>$request['email'],
            'password' => Hash::make($request['password']),
           


        ]);

        //send mail with maitrap
            // Mail::to($user->email)->send(new Verify($user));
            
            $url = (new Verify($user))->verificationUrl();



        return response()->json(['success' => true,'errors'=>null,'data'=>$user,'link_verify'=>$url], 200);
    }}

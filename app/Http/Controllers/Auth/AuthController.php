<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\Verify;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use openApi\Annotations as OA;

class AuthController extends Controller
{

      /**
        * @OA\Post(
        * path="/api/login",
        * operationId="authLogin",
        * tags={"Authentication"},
        * summary="User Login",
        * description="Login User Here",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"email", "password"},
        *               @OA\Property(property="email", type="email"),
        *               @OA\Property(property="password", type="password")
        *            ),
        *        ),
        *    ),
        *      @OA\Response(
        *          response=201,
        *          description="Login Successfully",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=200,
        *          description="Login Successfully",
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

    
    public function login(Request $request){
        try {
            $validated = $request->validate([
                'email'=> 'required',
                'password'=> 'required',

          ]);
          } catch (ValidationException $e) {
              // Validation failed, return a JSON response with validation errors
              return response()->json(['errors' => $e->validator->errors(),'data'=>null], 422);
          }
          $user = User::where('email', $request->email)->first();


    if (!$user ) {
            return response()->json(['success' => false, 'errors' => 'user not exist','data'=>null], 401);
    }
    if (!$user->email_verified_at) {
        return response()->json(['success' => false, 'errors' => 'Email not verified','data'=>null], 401);
}
        
    if(! Hash::check($request->password,$user->password)){
    return response()->json(['success' => false, 'error' => 'not correct password','data'=>null], 401);
        // response false user after enter password
    }
            $token = $user->createToken($request->server('HTTP_USER_AGENT'));
            $data = [
                'token' => $token->plainTextToken,
               
            ];
                     
            return response()->json(['success' => true, 'errors' => null,'data'=>$data], 200);
    }

    /**
 * @OA\Post(
 *     path="/api/logout",
 *     summary="Logout user",
 *     tags={"Authentication"},
 *     security={{"sanctum": {}}},
 *     @OA\Response(
 *         response=200,
 *         description="Successful logout",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="User logged out successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthenticated",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Unauthenticated")
 *         )
 *     )
 * )
 */
    public function logout(){


        $user = Auth::guard('sanctum')->user();


        if ($user) {
            $user->tokens()->delete();
        }
        return response()->json($user, true);

    }















}

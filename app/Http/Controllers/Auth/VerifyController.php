<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;

class VerifyController extends Controller
{
      
    /**
 * @OA\Post(
 *     path="/api/verify/{id}/{hash}",
 *     summary="Verify user email",
 *     tags={"Authentication"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="User ID",
 *         @OA\Schema(
 *             type="integer",
 *             format="int64"
 *         )
 *     ),
 *     @OA\Parameter(
 *         name="hash",
 *         in="path",
 *         required=true,
 *         description="Verification hash",
 *         @OA\Schema(
 *             type="string",
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="User or hash not found"
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Email verified successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example="true"),
 *             @OA\Property(property="errors", type="null"),
 *             @OA\Property(property="data", type="string", example="Verification successful")
 *         )
 *     )
 * )
 */
    public function verify(Request $request,$id,$hash){
        $user = User::find($id);

       
        if (!$user || sha1($user->getEmailForVerification()) !== $hash) {
            abort(404); 
        }

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            event(new Verified($user));
        }
        return response()->json(['success' => true, 'errors' => null,'data'=>'Verification successful'], 201);


    }

}

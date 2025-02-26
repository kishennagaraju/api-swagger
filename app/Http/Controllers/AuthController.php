<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Traits\Services\Auth;

use function response;

class AuthController extends Controller
{
    use Auth;

    /**
     * @OA\Post(
     *     path="/api/v1/user/login",
     *     summary="User Login",
     *     operationId="loginUser",
     *     tags={"User"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"email", "password"},
     *                 @OA\Property(
     *                     property="email",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string"
     *                 ),
     *                 example={"email": "admin@test.co.uk", "password": "admin"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     *
     */
    public function login(LoginRequest $request): \Illuminate\Http\JsonResponse
    {
        $response = [
            'status' => false,
            'data' => 'Login Failed'
        ];

        if ($tokenDetails = $this->getAuthService()->login($request, '0')) {
            $response = [
                'status' => true,
                'data' => $tokenDetails
            ];

            return response()->json($response)->setStatusCode(200);
        }

        return response()->json($response)->setStatusCode(401);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/user/logout",
     *     summary="Retrieve Single User by UUID",
     *     operationId="retrieveSingleUser",
     *     tags={"User"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     *
     */
    public function logout()
    {
        $response = [
            'status' => false,
            'data' => 'Logout Failed'
        ];

        if ($this->getAuthService()->logout()) {
            $response = [
                'status' => true,
                'data' => 'Logout Success'
            ];

            return response()->json($response)->setStatusCode(200);
        }

        return response()->json($response)->setStatusCode(401);
    }
}

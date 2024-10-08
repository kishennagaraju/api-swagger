<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\PasswordResets;
use App\Traits\Models\JwtTokens;
use App\Traits\Models\User;
use App\Traits\Services\User as UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use JwtTokens;
    use User;
    use UserService;

    /**
     * @OA\Get(
     *     path="/api/v1/user",
     *     summary="Retrieve logged in User Details",
     *     operationId="retrieveLoginUserDetails",
     *     security={{"bearerAuth": {}}},
     *     tags={"User"},
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     *
     */
    public function show()
    {
        $userDetails = $this->getUserModel()->getUserDetailsByUuid(request()->user->uuid);

        return response()->json($userDetails);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/user",
     *     summary="Create User",
     *     operationId="createUser",
     *     tags={"User"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"first_name","last_name","email","password","password_confirmation","avatar","address","phone_number"},
     *                 @OA\Property(
     *                     property="first_name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="last_name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password_confirmation",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="avatar",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="address",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="phone_number",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="is_marketing",
     *                     type="boolean"
     *                 ),
     *                 example={
     *                      "first_name": "Test",
     *                      "last_name": "User",
     *                      "email": "test@buckhill.co.uk",
     *                      "password": "password123",
     *                      "password_confirmation": "password123",
     *                      "avatar": "82110194-fdc6-4872-9adb-4776e28deac3",
     *                      "address": "11930 Damion Light Suite 642 Brigitteside, AZ 62654",
     *                      "phone_number": "+1-936-301-5409 | (938) 653-8850 | 820.279.3605",
     *                      "is_marketing": 1,
     *                 }
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Data"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function storeUser(CreateUserRequest $request): \Illuminate\Http\JsonResponse
    {
        return response()->json($this->getUserModel()->createUser($request->all()));
    }

    /**
     * @OA\Put(
     *     path="/api/v1/user/{uuid}",
     *     summary="Update User",
     *     operationId="updateUser",
     *     security={{"bearerAuth": {}}},
     *     tags={"User"},
     *     @OA\Parameter(
     *         description="Unique Identifier of User",
     *         in="path",
     *         name="uuid",
     *         required=true,
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"first_name","last_name","email","password","password_confirmation","avatar","address","phone_number"},
     *                 @OA\Property(
     *                     property="first_name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="last_name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password_confirmation",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="avatar",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="address",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="phone_number",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="is_marketing",
     *                     type="boolean"
     *                 ),
     *                 example={
     *                      "first_name": "Test",
     *                      "last_name": "Admin",
     *                      "email": "test@buckhill.co.uk",
     *                      "password": "password123",
     *                      "password_confirmation": "password123",
     *                      "avatar": "82110194-fdc6-4872-9adb-4776e28deac3",
     *                      "address": "11930 Damion Light Suite 642 Brigitteside, AZ 62654",
     *                      "phone_number": "+1-936-301-5409 | (938) 653-8850 | 820.279.3605",
     *                      "is_marketing": 1,
     *                 }
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Data"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function update(CreateUserRequest $request, string $uuid)
    {
        return response()->json($this->getUserModel()->updateUser($uuid, $request->all()));
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/user/{uuid}",
     *     summary="Delete User",
     *     operationId="deleteNonAdmin",
     *     security={{"bearerAuth": {}}},
     *     tags={"User"},
     *     @OA\Parameter(
     *         description="Unique Identifier of User",
     *         in="path",
     *         name="uuid",
     *         required=true,
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
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
    public function delete(): \Illuminate\Http\JsonResponse
    {
        $response = [
            'status' => false,
            'message' => 'User could not be Deleted'
        ];

        if ($this->getUserModel()->deleteUserByUuid(request()->user->uuid)) {
            $response = [
                'status' => true,
                'message' => 'User Deleted'
            ];
        }

        return response()->json($response);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/user/orders",
     *     summary="Retrieve All User Orders",
     *     operationId="retrieveLoginUserOrders",
     *     security={{"bearerAuth": {}}},
     *     tags={"User"},
     *     @OA\Parameter(
     *         description="Page Number",
     *         in="query",
     *         name="page",
     *         required=false,
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\Parameter(
     *         description="Pagination Limit Per Page",
     *         in="query",
     *         name="limit",
     *         required=false,
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\Parameter(
     *         description="Pagination Sort",
     *         in="query",
     *         name="sortBy",
     *         required=false,
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Parameter(
     *         description="Sort in Descending",
     *         in="query",
     *         name="desc",
     *         required=false,
     *         @OA\Schema(type="boolean"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function getOrdersForUser()
    {
        $userDetails = $this->getUserModel()->getUserDetailsByUuid(request()->user->uuid);

        return response()->json(
            $this->getUserService()->getAllOrdersForUser(
                $userDetails->id,
                ['order_status', 'payment', 'order_products']
            )
        );
    }

    /**
     * @OA\Post(
     *     path="/api/v1/user/forgot-password",
     *     summary="Forgot Password",
     *     operationId="forgotPassword",
     *     tags={"User"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"email"},
     *                 @OA\Property(
     *                     property="email",
     *                     type="string"
     *                 ),
     *                 example={
     *                      "email": "test@buckhill.co.uk"
     *                 }
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
     *         response=422,
     *         description="Unprocessable Data"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function forgotPassword(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email'
        ]);

        return response()->json([
            'status' => true,
            'message' => $this->getUserService()->forgotPassword($validated['email'])
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/user/reset-password",
     *     summary="Reset Password",
     *     operationId="resetPassword",
     *     tags={"User"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"token","email","password","password_confirmation"},
     *                 @OA\Property(
     *                     property="token",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password_confirmation",
     *                     type="string"
     *                 ),
     *                 example={
     *                      "token": "MV3XsE3dAYkQHFuc7mRyw2absoAdptl5uUTLLUkrZVmD3EjjDk",
     *                      "email": "test@buckhill.co.uk",
     *                      "password": "admin1234",
     *                      "password_confirmation": "admin1234"
     *                 }
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
     *         response=422,
     *         description="Unprocessable Data"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
        if ($this->getUserService()->resetPassword($request->all())) {
            return response()->json([
                'status' => true,
                'message' => 'Password Reset Successfull'
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'could not reset password'
        ])->setStatusCode(500);
    }
}

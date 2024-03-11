<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    /**
    * @OA\Post(
    *   path="/api/auth/login",
    *   summary="Login",
    *   tags={"Login"},
    *   security={{ "bearerAuth": {} }},
    *       @OA\RequestBody(
    *           @OA\JsonContent(
    *               allOf={
    *                   @OA\Schema(
    *                       @OA\Property(property="email", type="string", example="test@example.com"),
    *                       @OA\Property(property="password", type="string", example="password"),
    *                      )
    *               }
    *           )
    *       ),
    *       @OA\Response(
    *           response=200,
    *           description="OK",
    *           @OA\JsonContent(
    *                       @OA\Property(property="access_token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sYXJhdmVsdGVzdC5sb2NhbFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTcwOTk4NTc3NSwiZXhwIjoxNzA5OTg5Mzc1LCJuYmYiOjE3MDk5ODU3NzUsImp0aSI6ImJDYVZBZTFvVG51VTVzNjciLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.VYrNSCV_Fja2jDwSMXPJ1MzsjJuvQlXbTC-pkIriejo"),
    *                       @OA\Property(property="token_type", type="string", example="bearer"),
    *                       @OA\Property(property="expires_in", type="integer", example="3600"),
    *                      )
    *               
    *           )
    *       ),
    * @OA\Get(
    *   path="/api/auth/info",
    *   summary="Get user info",
    *   tags={"Info"},
    *   security={{ "bearerAuth": {} }},
    *       @OA\Response(
    *           response=200,
    *           description="OK",
    *           @OA\JsonContent(
    *                       @OA\Property(property="id", type="integer", example="1"),
    *                       @OA\Property(property="name", type="string", example="Test User"),
    *                       @OA\Property(property="email", type="string", example="test@example"),
    *                       @OA\Property(property="email_verified_at", type="string", example="2024-03-07T18:01:48.000000Z"),
    *                       @OA\Property(property="created_at", type="string", example="2024-03-07T18:01:48.000000Z"),
    *                       @OA\Property(property="updated_at", type="string", example="2024-03-07T18:01:48.000000Z"),
    *                      )
    *               
    *           )
    *       ),
    * @OA\Get(
    *   path="/api/auth/logout",
    *   summary="Logout account",
    *   tags={"Logout"},
    *   security={{ "bearerAuth": {} }},
    *       @OA\Response(
    *           response=200,
    *           description="OK",
    *           @OA\JsonContent(
    *                       @OA\Property(property="message", type="string", example="Successfully logged out"),
    *                      )
    *               
    *           )
    *       )
    */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth('api')->attempt($credentials,$remember = true)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
       // $user = auth()->getUser();
        //auth()->login($user, $remember = true);
        return $this->respondWithToken($token);
    }
    public function info()
    {   
        $user = auth('api')->getUser();
        return response()->json($user);
    }
    public function me()
    {
        return response()->json(auth('api')->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
           //'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}
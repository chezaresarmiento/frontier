<?php

/**
 * @OA\Info(
 *     title="Wishlist API",
 *     version="1.0.0",
 *     description="A simple Laravel API for Wishlist management (register, login, products, wishlist)."
 * ),
 * @OA\Server(
 *     url="http://localhost:9000",
 *     description="Local development server"
 * )
 */

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    
    public function __construct(private AuthService $authService) {}


    /**
 * @OA\Post(
 *     path="/api/register",
 *     tags={"Auth"},
 *     summary="Register a new user",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name","email","password"},
 *             @OA\Property(property="name", type="string", example="Cesar"),
 *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
 *             @OA\Property(property="password", type="string", format="password", example="secret123")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="User registered successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="id", type="integer"),
 *             @OA\Property(property="name", type="string"),
 *             @OA\Property(property="email", type="string")
 *         )
 *     ),
 *     @OA\Response(response=422, description="Validation error")
 * )
 */

    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name'     => 'required|string',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $user = $this->authService->register(
            $request->name,
            $request->email,
            $request->password
        );

        return response()->json($user, 201);
    }


/**
 * @OA\Post(
 *     path="/api/login",
 *     tags={"Auth"},
 *     summary="Login user",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email","password"},
 *             @OA\Property(property="email", type="string", format="email"),
 *             @OA\Property(property="password", type="string", format="string", example="secret123")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful login",
 *         @OA\JsonContent(
 *             @OA\Property(property="token", type="string"),
 *             @OA\Property(property="expires_at", type="string", format="date-time")
 *         )
 *     ),
 *     @OA\Response(response=401, description="Invalid credentials")
 * )
 */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $token = $this->authService->login($request->email, $request->password);

        if (!$token) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        return response()->json(['token' => $token->token, 'expires_at' => $token->expires_at]);
    }

    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();
        $this->authService->logout($user, $request->bearerToken());

        return response()->json(['message' => 'Logged out']);
    }
}
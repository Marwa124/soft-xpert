<?php

namespace App\Http\Controllers\Api;

use App\Helpers\CookieHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\ApiResource;
use App\Models\User;
use App\Traits\HandleExceptions;
use Illuminate\Support\Facades\Auth;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    use HandleExceptions;

    public function register(RegisterRequest $request)
    {
        try {
            $user = User::create($request->validated());
            $token = JWTAuth::fromUser($user);

            return response()->json(new ApiResource([
                'user' => $user,
                'access_token' => $token
            ], 201, 'Success registration'), 201);
        } catch (\Exception $e) {
            $error = $this->handleException($e);

            return response()->json(new ApiResource(null, $error['status'], $error['message']), $error['status']);
        }
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only(['email', 'password']);

        $token = JWTAuth::attempt($credentials);
        if (!$token) {
            return response()->json(new ApiResource(null, 401, 'Your credential is mismatched. Try again!'), 401);
        }

        $cookie = CookieHelper::makeCookie('accessToken', $token);
        return response()->json(new ApiResource($token, 200, 'Login success'))
            ->cookie($cookie);
    }

    public function logout()
    {
        $cookie = cookie()->forget('accessToken');
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()
            ->json(new ApiResource(null, 200, 'Logout success'))
            ->withCookie($cookie);
    }

    public function user()
    {
        try {
            $user = Auth::user();
            $user['role'] = $user->getRoleNames()->first();
            return response()->json(new ApiResource($user, 200, 'Success get user profile'));
        } catch (\Exception $e) {
            $error = $this->handleException($e);
            return response()->json(new ApiResource(null, $error['status'], $error['message']), $error['status']);
        }
    }

    public function refresh()
    {
        try {
            $oldToken = JWTAuth::getToken();
            $newToken = JWTAuth::refresh($oldToken);
            JWTAuth::invalidate($oldToken);

            $cookie = CookieHelper::makeCookie('accessToken', $newToken);
            return response()->json(new ApiResource($newToken, 200, 'Successful generate new token'))
                ->cookie($cookie);
        } catch (\Exception $e) {
            return response()->json(new ApiResource(null, 403, 'Token invalid or expired'), 403);
        }
    }
}

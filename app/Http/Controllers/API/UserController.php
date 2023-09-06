<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Laravel\Fortify\Rules\Password as RulesPassword;
use PhpParser\Node\Stmt\TryCatch;

class UserController extends Controller
{
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);

            $credentials = request(['email', 'password']);
            if (!Auth::attempt($credentials)) {
                return ResponseFormatter::error('Unauthorized', 401);
            }

            $user = User::where('email', $request->email)->first();
            if (!Hash::check($request->password, $user->password)) {
                throw new Exception('Invalid Password');
            }

            $tokenResult = $user->createToken('authToken')->plainTextToken;
            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user
            ], 'Login Success');
        } catch (Exception $e) {
            return ResponseFormatter::error('Login Failed');
        }
    }
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'string', 'max:255', 'unique:users'],
                'password' => ['required', 'string', new RulesPassword],
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $tokenResult = $user->createToken('authToken')->plainTextToken;
            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user,
            ], 'Regiser Success');
        } catch (Exception $err) {
            return ResponseFormatter::error($err->getMessage());
        }
    }
    public function fetch()
    {
        try {
            $user = Auth::user();
            return ResponseFormatter::success($user, 'Fetch User Success');
        } catch (Exception $err) {
            return ResponseFormatter::error('Fetch User Failed');
        }
    }
    public function logout(Request $request)
    {
        try {
            $tokenResult = $request->user()->currentAccessToken()->delete();
            return ResponseFormatter::success($tokenResult, 'Logout Success');
        } catch (Exception $err) {
            return ResponseFormatter::error('Logout Failed');
        }
    }
}

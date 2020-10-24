<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Register new user
     * @method POST
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);


        config('permissions.default_super_admin_email') == $user->email ?
            $user->assignRole('Super_Admin') : $user->assignRole('User') ;

        return response()->json([
            'message'   => 'user created successfully',
            'data'      => [
                'name'  =>  $user->name,
                'email' => $user->email
            ]
        ],201);
    }

    /**
     * @method POST
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Authentication passed...
            return response()->json([
                'message'   =>  'you\'re logged in',
                'data'      => [
                    'email' => \auth()->user()->email,
                    'name' => \auth()->user()->name,
                ]
            ]);
        }

        return \response()->json([
            'message'   =>  'email or password is incorrect',
            'errors'    => [
                'Unprocessable Entity'
            ]
        ],422);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function user()
    {
        return response()->json([
            'message'   => 'user information',
            'data'      => [
                'name' => auth()->user()->name,
                'email' => auth()->user()->email,
            ]
        ],200);
    }

    /**
     * @method GET
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        Auth::logout();
        return \response()->json([
            'message'   =>  'logged out successfully',
            'data'    => [
                'you\'re logged out'
            ]
        ],200);
    }
}

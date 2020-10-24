<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function show()
    {
        return response()->json([
            'message'   => 'user information',
            'data'      => [
                'name'          => auth()->user()->name,
                'email'         => auth()->user()->email,
                'notifications' => auth()->user()->unreadNotifications
            ]
        ],200);
    }
}

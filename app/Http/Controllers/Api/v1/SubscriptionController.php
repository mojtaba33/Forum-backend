<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Thread;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function subscribe(Thread $thread)
    {
        auth()->user()->subscription()->attach($thread);

        return response()->json([
            'message'   => 'add to your subscriptions successfully'
        ],200);
    }

    public function unsubscribe(Thread $thread)
    {
        auth()->user()->subscription()->detach($thread);

        return response()->json([
            'message'   => 'remove from your subscriptions successfully'
        ],200);
    }
}

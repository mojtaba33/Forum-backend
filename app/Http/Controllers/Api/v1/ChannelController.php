<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Channel;
use Illuminate\Http\Request;

class ChannelController extends Controller
{
    public function get()
    {
        $channels = Channel::latest()->paginate(20);
        return response()->json([
            'message'   => 'success',
            'data'      =>  $channels,
        ]);
    }
}

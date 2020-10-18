<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Channel;
use Illuminate\Http\Request;

class ChannelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $channels = Channel::latest()->paginate(20);
        return response()->json([
            'message'   => 'success',
            'data'      =>  $channels,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'  =>  'required|max:255'
        ]);

        $channels = Channel::create([
            'title' => $request->input('title'),
        ]);

        return response()->json([
            'message'   => 'channel created successfully',
            'data'      =>  $channels,
        ],201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Channel  $channel
     * @return \Illuminate\Http\Response
     */
    public function show(Channel $channel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Channel  $channel
     * @return \Illuminate\Http\Response
     */
    public function edit(Channel $channel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Channel  $channel
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Channel $channel)
    {
        $request->validate([
            'title'  =>  'required|max:255'
        ]);

        $channel->update([
            'title' => $request->input('title'),
        ]);

        return response()->json([
            'message'   => 'channel updated successfully',
            'data'      =>  $channel,
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Channel  $channel
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Channel $channel)
    {
        $channel->delete();

        return response()->json([
            'message'   => 'channel deleted successfully',
        ],200);
    }
}

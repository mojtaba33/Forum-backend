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
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title'  =>  'required|max:255'
        ]);

        $channel = Channel::find($id);

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
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $channel = Channel::find($id);

        $channel->delete();

        return response()->json([
            'message'   => 'channel deleted successfully',
        ],200);
    }
}

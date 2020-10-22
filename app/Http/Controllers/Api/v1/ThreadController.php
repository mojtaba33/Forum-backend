<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Thread;
use Illuminate\Http\Request;

class ThreadController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['store','update']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $threads = Thread::where('flag',1)->paginate(20);
        return response()->json([
            'message' => 'success',
            'data'    => $threads
        ],200);
    }

    /**
     * Show the form for creating a new resource.
     *
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
            'title'      => 'required',
            'content'    => 'required',
            'channel_id' => 'required'
        ]);

        Thread::create([
            'title'      => $request->input('title'),
            'content'    => $request->input('content'),
            'channel_id' => $request->input('channel_id'),
            'user_id'    => auth()->user()->id,
        ]);

        return \response()->json([
            'message'   => 'thread create successfully',
        ],201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $thread = Thread::where('flag',1)->where('slug',$slug)->first();
        return response()->json([
            'message' => 'success',
            'data'  => $thread
        ],200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Thread $thread
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Thread $thread, Request $request)
    {
        $this->authorize('checkUserCanUpdateOrDeleteThread', $thread);

        $request->validate([
            'title'      => 'required',
            'content'    => 'required',
            'channel_id' => 'required'
        ]);

        $thread->update([
            'title'      => $request->input('title'),
            'content'    => $request->input('content'),
            'channel_id' => $request->input('channel_id'),
        ]);

        return \response()->json([
            'message'   => 'thread create successfully',
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Thread $thread
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Thread $thread)
    {
        $this->authorize('checkUserCanUpdateOrDeleteThread', $thread);

        $thread->delete();

        return \response()->json([
            'message'   => 'thread deleted successfully',
        ],200);
    }

    /**
     * @param Thread $thread
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function setBestAnswer(Thread $thread , Request $request)
    {
        $request->validate([
            'best_answer_id' => 'required'
        ]);

        $this->authorize('checkUserCanUpdateOrDeleteThread', $thread);

        $thread->update([
            'best_answer_id' => $request->input('best_answer_id')
        ]);

        return \response()->json([
            'message'   => 'best answer added successfully',
        ],200);
    }
}

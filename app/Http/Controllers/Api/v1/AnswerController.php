<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Thread;
use App\Notifications\ThreadAnswered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class AnswerController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['store','update']);
        $this->middleware('check.suspended')->only(['store','update']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required',
            'thread_id' => 'required'
        ]);

        $answer = auth()->user()->answers()->create([
            'thread_id' => $request->input('thread_id'),
            'content' => $request->input('content'),
        ]);

        $thread = Thread::find($request->input('thread_id'));

        // send notifications to users who subscribe this thread
        $this->sendNotification($thread);

        if (auth()->user()->id != $thread->user_id)
            auth()->user()->increment('score',10);

        return response()->json([
            'message' => 'answer submitted successfully',
            'data'  => $answer
        ],201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function show(Answer $answer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function edit(Answer $answer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Answer $answer
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, Answer $answer)
    {
        $this->authorize('checkUserCanUpdateOrDeleteAnswer',$answer);

        $request->validate([
            'content' => 'required',
        ]);

        $answer->update([
            'content' => $request->input('content'),
        ]);

        return response()->json([
            'message' => 'answer updated successfully',
            'data'  => $answer
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Answer $answer
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Answer $answer)
    {
        $this->authorize('checkUserCanUpdateOrDeleteAnswer',$answer);

        $answer->delete();

        return response()->json([
            'message' => 'answer deleted successfully',
            'data'  => $answer
        ],200);
    }

    /**
     * @param Thread $thread
     */
    protected function sendNotification(Thread $thread)
    {
        $users = $thread->subscription()->get();
        Notification::send($users, new ThreadAnswered($thread));
    }
}

<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use Illuminate\Http\Request;

class AnswerController extends Controller
{
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
}

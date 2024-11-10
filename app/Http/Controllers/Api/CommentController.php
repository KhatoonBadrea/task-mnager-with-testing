<?php

namespace App\Http\Controllers\Api;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Services\CommentService;
use App\Http\Controllers\Controller;
use App\Http\Requests\comment\StoreCommentRequest;
use App\Http\Requests\comment\UpdateCommentRequest;

class CommentController extends Controller
{
    protected $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request)
    {
        $validationdata = $request->validated();
        $response = $this->commentService->addComment($validationdata);
        if (!$response) {
            return $this->error();
        } else {
            return $this->success();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($commentId)
    {
        $comment = $this->commentService->getComment($commentId);
    
        if (!$comment) {
            return $this->error(); 
        }
    
        return response()->json($comment); 
    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentRequest $request, $commentId)
    {
        $validationData = $request->validated();
        $response = $this->commentService->updateComment($validationData, $commentId);

        if (!$response) {
            return $this->error(); 
        }

        return $this->success(); 
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($commentId)
    {
        $response = $this->commentService->deleteComment($commentId);
    
        if (!$response) {
            return $this->error(); 
        }
    
        return $this->success(); 
    }
    
}

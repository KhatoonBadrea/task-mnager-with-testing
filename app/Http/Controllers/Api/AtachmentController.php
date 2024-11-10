<?php

namespace App\Http\Controllers\Api;

use App\Models\Task;
use App\Models\Attachment;
use Illuminate\Http\Request;
use App\Services\TaskService;
use App\Services\AttachmentService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class AtachmentController extends Controller
{
    protected $attachmentService;

    public function __construct(AttachmentService $attachmentService)
    {
        $this->attachmentService = $attachmentService;
    }

    public function uploadAttachment(Request $request, $taskId)
    {
        try {
            $this->validate($request, [
                'attachment' => 'required|file|max:5120', 
            ]);

            $file = $request->file('attachment');
            $this->attachmentService->uploadAttachment($taskId, $file);

            return response()->json(['message' => 'File uploaded successfully.']);
        } catch (\Exception $e) {
            Log::error('Error in AtachmentController@uploadAttachment: ' . $e->getMessage());
            return response()->json(['error' => 'File upload failed.'], 500);
        }
    }
    
}

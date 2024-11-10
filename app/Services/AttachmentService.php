<?php
namespace App\Services;

use App\Models\Task;
use App\Models\Attachment;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class AttachmentService
{
    public function uploadAttachment($taskId, $file)
    {
        $task = Task::find($taskId);
        if (!$task) {
            throw new \Exception('Task not found');
        }

        if ($file->isValid() && $this->validateFile($file)) {
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('attachments', $fileName, 'private'); 

            $task->attachments()->create([
                'file_name' => $fileName,
                'file_path' => $filePath,
                'file_size' => $file->getSize(),
            ]);

            return true;
        }

        throw new \Exception('Invalid file upload');
    }

    protected function validateFile($file)
    {
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf', 'docx'];
        $maxFileSize = 1024 * 1024 * 5; 

        return in_array($file->extension(), $allowedExtensions) && $file->getSize() <= $maxFileSize;
    }
}
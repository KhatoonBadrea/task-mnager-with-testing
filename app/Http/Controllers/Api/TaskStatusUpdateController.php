<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Jobs\DailyReportService;
use App\Models\TaskStatusUpdate;
use App\Http\Controllers\Controller;

class TaskStatusUpdateController extends Controller
{
    public function dailyReport()
    {
        $tasks = TaskStatusUpdate::select('task_id','status','type')->whereDate('created_at', today())->get();

        DailyReportService::dispatch($tasks);

         return response()->json([
            'status' => 'success',
            'data' => $tasks,
        ], 200);
    }

  
}
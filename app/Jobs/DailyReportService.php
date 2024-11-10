<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Models\TaskStatusUpdate;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels; 
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class DailyReportService implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $daily_report;
    /**
     * Create a new job instance.
     */
    public function __construct($daily_report)
    {
        $this->daily_report=$daily_report;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (!$this->daily_report) {
            $this->daily_report = TaskStatusUpdate::select('task_id','status','type')-> whereDate('created_at', today())->get();
        }

        Log::info('Daily Task Report:', $this->daily_report->toArray());
    }
}

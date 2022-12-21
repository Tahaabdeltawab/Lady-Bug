<?php

namespace App\Console\Commands;

use App\Models\Task;
use Illuminate\Console\Command;

class NotifyTaskCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alarm:task';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'notify users by coming tasks';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $alarmed_tasks = Task::with('business.users')->open()->where(function($q){
            $q->where('date', today()->addDay())->orWhere('date', today());
        })->get();
        foreach($alarmed_tasks as $task){
            foreach($task->business->users as $user){
                $user->notify(new \App\Notifications\TaskAlarm($task));
            }
        }
    }
}

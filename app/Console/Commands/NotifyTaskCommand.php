<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class NotifyTaskCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:task';

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

    }
}

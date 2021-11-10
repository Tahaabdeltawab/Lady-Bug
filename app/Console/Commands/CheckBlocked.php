<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CheckBlocked extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'checkBlocked';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'unblock users if their block duration ended';

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
        // get blocked user where blocked_until not null
        // if blocked_until is after today activate him and make until = null
        $users = User::blocked()->where('blocked_until', '<', today())
        ->update(['status' => 'accepted', 'blocked_until' => null]);
    }
}

<?php

namespace App\Console\Commands;

use App\Models\RoleUser;
use App\Models\User;
use Illuminate\Console\Command;

class CheckBusinessRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'checkBusinessRole';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'revoke user role from a business if their duration ended';

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
        RoleUser::ended()->delete();
    }
}

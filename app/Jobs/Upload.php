<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


class Upload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public $request;
    public $post;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($request, $post)
    {
        $this->request = $request;
        $this->post = $post;
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $oneasset = app('\App\Http\Controllers\API\BusinessAPIController')->store_file($this->request['assets']);
        $this->post->assets()->create($oneasset);
    }
}

<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Repositories\AssetRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
        // info('request', $request);
        $currentDate = Carbon::now()->toDateString();
        $assetsname = 'post-'.$currentDate.'-'.uniqid().'.'.$this->request['assets']->getClientOriginalExtension();
        $assetssize = $this->request['assets']->getSize(); //size in bytes 1k = 1000bytes
        $assetsmime = $this->request['assets']->getClientMimeType();
                
        $path = $this->request['assets']->storeAs('assets/posts', $assetname, 's3');
        // $path = Storage::disk('s3')->putFileAs('assets/images', $asset, $assetname);
        
        $url  = Storage::disk('s3')->url($path);
        
        $asset = $this->post->assets()->create([
            'asset_name'        => $assetsname,
            'asset_url'         => $url,
            'asset_size'        => $assetssize,
            'asset_mime'        => $assetsmime,
        ]);
    }
}

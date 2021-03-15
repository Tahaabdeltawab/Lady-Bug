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

class Upload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public $asset;
    public $post;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($asset, $post)
    {
        $this->asset = $asset;
        $this->post  = $post;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $currentDate = Carbon::now()->toDateString();
        $assetname = 'post-'.$currentDate.'-'.uniqid().'.'.$this->asset->getClientOriginalExtension();
        $assetsize = $this->asset->getSize(); //size in bytes 1k = 1000bytes
        $assetmime = $this->asset->getClientMimeType();
                
        $path = $this->asset->storeAs('assets/posts', $assetname, 's3');
        // $path = Storage::disk('s3')->putFileAs('assets/images', $asset, $assetname);
        
        $url  = Storage::disk('s3')->url($path);
        
        $saved_asset = (new AssetRepository(app()))->create([
            'asset_name'        => $assetname,
            'asset_url'         => $url,
            'asset_size'        => $assetsize,
            'asset_mime'        => $assetmime,
            'assetable_type'    => 'post'
        ]);

        $this->post->assets()->attach($saved_asset->id);
    }
}

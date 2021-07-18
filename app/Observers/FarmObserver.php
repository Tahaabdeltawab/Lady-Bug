<?php

namespace App\Observers;

use App\Http\Helpers\Compatibility;
use App\Models\Farm;

class FarmObserver
{
    /**
     * Handle the farm "created" event.
     *
     * @param  \App\Models\farm  $farm
     * @return void
     */
    public function created(farm $farm)
    {
        //
    }

    public function saving(farm $farm)
    {
        //
    }

    public function saved(farm $farm)
    {
        // (new Compatibility())->calculate_compatibility($farm);
    }

    public function retrieved(farm $farm)
    {
        //
    }

    /**
     * Handle the farm "updated" event.
     *
     * @param  \App\Models\farm  $farm
     * @return void
     */
    public function updated(farm $farm)
    {
        //
    }

    /**
     * Handle the farm "deleted" event.
     *
     * @param  \App\Models\farm  $farm
     * @return void
     */
    public function deleted(farm $farm)
    {
        //
    }

    /**
     * Handle the farm "restored" event.
     *
     * @param  \App\Models\farm  $farm
     * @return void
     */
    public function restored(farm $farm)
    {
        //
    }

    /**
     * Handle the farm "force deleted" event.
     *
     * @param  \App\Models\farm  $farm
     * @return void
     */
    public function forceDeleted(farm $farm)
    {
        //
    }
}

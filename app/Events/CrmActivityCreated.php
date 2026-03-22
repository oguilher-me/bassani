<?php

namespace App\Events;

use App\Models\CrmActivity;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CrmActivityCreated
{
    use Dispatchable, SerializesModels;

    public $activity;

    /**
     * Create a new event instance.
     */
    public function __construct(CrmActivity $activity)
    {
        $this->activity = $activity;
    }
}

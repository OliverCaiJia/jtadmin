<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\PermChangeEvent' => [
            'App\Listeners\PermChangeListener',
        ],
        'App\Events\UserActionEvent' => [
            'App\Listeners\UserActionListener',
        ],
        'App\Events\OrderActionEvent' => [
            'App\Listeners\OrderActionListener',
        ],
        'App\Events\OperationLogEvent' => [
            'App\Listeners\OperationLogListener',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}

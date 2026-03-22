<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        \App\Models\CrmOpportunity::observe(\App\Observers\CrmOpportunityObserver::class);

        \Illuminate\Support\Facades\Event::listen(
            \App\Events\CrmActivityCreated::class,
            \App\Listeners\ScheduleTaskReminder::class
        );
    }
}

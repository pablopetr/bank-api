<?php

namespace App\Providers;

use App\Events\TransferCompleted;
use App\Listeners\PublishTransferCompleted;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use MongoDB\Laravel\Eloquent\Model;

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
        if(! app()->isProduction()) {
            Model::preventLazyLoading();
            Model::preventAccessingMissingAttributes();
        }

        Event::listen(TransferCompleted::class, PublishTransferCompleted::class);
    }
}

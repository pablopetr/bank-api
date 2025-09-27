<?php

namespace App\Providers;

use App\Events\TransferCompleted;
use App\Events\TransferFailed;
use App\Events\UserApproved;
use App\Events\UserCreated;
use App\Listeners\PublishedTransferFailed;
use App\Listeners\PublishTransferCompleted;
use App\Listeners\PublishUserApproved;
use App\Listeners\PublishUserCreated;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use MongoDB\Laravel\Eloquent\Model;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        if (! app()->isProduction()) {
            Model::preventLazyLoading();
            Model::preventAccessingMissingAttributes();
        }

        Event::listen(TransferCompleted::class, PublishTransferCompleted::class);
        Event::listen(TransferFailed::class, PublishedTransferFailed::class);
        Event::listen(UserCreated::class, PublishUserCreated::class);
        Event::listen(UserApproved::class, PublishUserApproved::class);
    }
}

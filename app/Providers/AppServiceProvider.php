<?php

namespace App\Providers;

use App\Models\Employee;
use App\Queues\HighPriorityQueue;
use App\Queues\LowPriorityQueue;
use Illuminate\Support\ServiceProvider;
use Lorisleiva\Actions\Facades\Actions;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Actions::registerCommands();
    }
}

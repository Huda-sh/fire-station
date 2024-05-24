<?php

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

(new Illuminate\Console\Scheduling\Schedule)->call(function () {
    $queue = app(\App\Queues\LowPriorityQueue::class);
    $queue->add(new \App\Models\Call(fake()->firstName()));
})->everySecond();

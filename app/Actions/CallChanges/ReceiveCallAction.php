<?php

namespace App\Actions\CallChanges;

use App\Enums\CallPriority;
use App\Models\Call;
use Illuminate\Console\Command;
use Lorisleiva\Actions\Concerns\AsAction;

class ReceiveCallAction
{
    use AsAction;

    public string $commandSignature = 'call:dispatch';

    public function handle()
    {

    }

    public function asCommand(Command $command): void
    {
        while (true) {
            Call::create([
                'name' => fake()->firstName(),
                'priority' => CallPriority::getRandomValue(),
                'duration'=>rand(6,10),
            ]);
            $command->info("call made");
            sleep(1);
        }
    }
}

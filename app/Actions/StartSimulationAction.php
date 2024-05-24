<?php

namespace App\Actions;

use App\Actions\CallChanges\ChangePriorityAction;
use App\Actions\CallChanges\HandleHighPriorityCallAction;
use App\Actions\CallChanges\HandleLowPriorityCallAction;
use App\Actions\CallChanges\UpdateCallsAction;
use App\Enums\CallPriority;
use App\Models\Call;
use App\Queues\LowPriorityQueue;
use Illuminate\Console\Command;
use JetBrains\PhpStorm\ArrayShape;
use Lorisleiva\Actions\Concerns\AsAction;

class StartSimulationAction
{
    use AsAction;

    public string $commandSignature = 'call:simulate';

    /**
     * @return array{low_calls: mixed, high_calls: mixed, employees: mixed}
     */
    #[ArrayShape(['low_calls' => "mixed", 'high_calls' => "mixed", 'employees' => "mixed"])]
    public function handle($i): array
    {
        HandleHighPriorityCallAction::run();
        HandleLowPriorityCallAction::run();

        if ($i % 3 == 0) {
            $random_low_call = Call::where('priority', CallPriority::LOW)->inRandomOrder()->first();
            ChangePriorityAction::run($random_low_call);
        }

        UpdateCallsAction::run();

        $low_calls = Call::where('priority', CallPriority::LOW)->whereDoesntHave('employee')->get();
        $high_calls = Call::where('priority', CallPriority::HIGH)->whereDoesntHave('employee')->get();
        $employees = PrepareDataAction::run();

        return [
            'low_calls' => $low_calls,
            'high_calls' => $high_calls,
            'employees' => $employees
        ];
    }

    public function asCommand(Command $command): void
    {
        system('clear');
        $choice = $command->choice('Do you want to continue the previous simulation or start new?', ['continue', 'start new']);
        try {
            PrepareDataAction::run($choice);
        } catch (\throwable $e) {
            $command->error($e->getMessage());
            return;
        }
        $i = 1;
        while (true) {
            sleep(1);
            $data = $this->handle($i);
            $i++;
            DisplayUpdatesAction::run($command, $data['employees'], $data['low_calls'], $data['high_calls']);
        }
    }
}

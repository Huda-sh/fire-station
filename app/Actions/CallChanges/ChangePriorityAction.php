<?php

namespace App\Actions\CallChanges;

use App\Enums\CallPriority;
use App\Models\Call;
use Lorisleiva\Actions\Concerns\AsAction;

class ChangePriorityAction
{
    use AsAction;

    public function handle($random_low_call): void
    {
        if ($random_low_call) {
            $temp_data = $random_low_call->toArray();
            $random_low_call->delete();
            Call::create([
                'name' => $temp_data['name'],
                'priority' => CallPriority::HIGH,
                'duration' => $temp_data['duration']
            ]);
        }
    }
}

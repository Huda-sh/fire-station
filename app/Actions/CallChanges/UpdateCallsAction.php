<?php

namespace App\Actions\CallChanges;

use App\Models\Call;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateCallsAction
{
    use AsAction;

    public function handle()
    {
        $calls = Call::whereHas('employee')->get();
        foreach ($calls as $call) {
            $new_duration = $call->duration;
            Call::find($call->id)->update([
                'duration' => $new_duration-1,
            ]);
        }
        Call::where('duration', '<=', 0)->delete();
    }
}

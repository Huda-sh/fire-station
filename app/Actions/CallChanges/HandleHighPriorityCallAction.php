<?php

namespace App\Actions\CallChanges;

use App\Enums\CallPriority;
use App\Enums\EmployeeLevel;
use App\Models\Call;
use App\Models\Employee;
use Lorisleiva\Actions\Concerns\AsAction;

class HandleHighPriorityCallAction
{
    use AsAction;

    public function handle(): void
    {
        $employees = Employee::whereIn('level', [EmployeeLevel::MANAGER, EmployeeLevel::DIRECTOR])
            ->whereNull('call_id')
            ->orderBy('level')
            ->get()->toArray();

        $calls = Call::where('priority', CallPriority::HIGH)
            ->whereDoesntHave('employee')
            ->limit(sizeof($employees))
            ->get()->toArray();

        for ($i = 0; $i < sizeof($calls); $i++) {
            Employee::find($employees[$i]['id'])->update([
                'call_id' => $calls[$i]['id'],
            ]);
        }
    }
}

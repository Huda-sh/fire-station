<?php

namespace App\Actions;

use App\Models\Employee;
use Exception;
use Lorisleiva\Actions\Concerns\AsAction;

class PrepareDataAction
{
    use AsAction;

    /**
     * @param string $choice
     * @return array
     * @throws Exception
     */
    public function handle(string $choice="continue"): array
    {
        if ($choice=='start new'){
            StartNewAction::run();
        }
        $employees = Employee::with(['call' => function($query) {
            $query->select('id', 'name');
        }])->get();
        $employees_array = [];
        foreach ($employees as $key => $employee){
            $employees_array[] = [
                'id'=>$employee->id,
                'name'=>$employee->name,
                'level'=>$employee->level->name,
                'caller'=>$employee->call?->name,
            ];
        }
        return $employees_array;
    }
}

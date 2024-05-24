<?php

namespace App\Actions;

use App\Models\Call;
use Illuminate\Console\Command;
use Lorisleiva\Actions\Concerns\AsAction;
use Symfony\Component\Console\Helper\Table;
use function MongoDB\BSON\toJSON;

class DisplayUpdatesAction
{
    use AsAction;

    /**
     * @param Command $command
     * @param $employees
     * @param $low_calls
     * @param $high_calls
     * @return void
     */
    public function handle(Command &$command, $employees, $low_calls, $high_calls): void
    {
        system('clear');
        $headers = ['id', 'name', "level", "caller"];
        $table = new Table($command->getOutput());

        $table->setHeaders($headers)->setRows($employees);
        $table->render();
        $low_str = "Low queue:\t";
        $high_str = "High queue:\t";
        foreach ($low_calls as $item)
            $low_str .= " -> " . $item->name;
        foreach ($high_calls as $item)
            $high_str .= " -> " . $item->name;
        $command->info($high_str);
        $command->warn($low_str);
    }
}

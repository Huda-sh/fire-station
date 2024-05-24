<?php

namespace App\Actions;

use App\Models\Call;
use App\Models\Employee;
use Exception;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class StartNewAction
{
    use AsAction;

    /**
     * @throws Exception
     */
    public function handle(): void
    {
        DB::table('calls')->truncate();
        DB::table('employees')->truncate();
        $contents = $this->GetFileContents();
        DB::table('employees')->insert($contents);
    }

    /**
     * @throws Exception
     */
    function GetFileContents(): mixed
    {
        $filePath = base_path('employees.json');
        if (file_exists($filePath)) {
            $contents = file_get_contents($filePath);
            return json_decode($contents, true);
        } else {
            throw new Exception('you need to supply the system employees to run the application');
        }
    }
}

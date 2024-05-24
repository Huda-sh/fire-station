<?php

namespace Tests\Unit;

use App\Actions\CallChanges\ChangePriorityAction;
use App\Enums\CallPriority;
use App\Enums\EmployeeLevel;
use App\Models\Call;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChangePriorityTest extends TestCase
{
    use RefreshDatabase;

    public function testLowPriorityCallWasDeleted()
    {
        // Create a low priority call
        $lowPriorityCall = Call::create([
            'name' => fake()->firstName(),
            'duration' => 3,
            'priority' => CallPriority::LOW
        ]);

        app(ChangePriorityAction::class)->handle($lowPriorityCall);

        $this->assertDatabaseMissing('calls',['id'=>$lowPriorityCall->id]);
    }
    public function testHighPriorityCallWasPushedAgain()
    {
        // Create a low priority call
        $lowPriorityCall = Call::create([
            'name' => fake()->firstName(),
            'duration' => 3,
            'priority' => CallPriority::LOW
        ]);

        app(ChangePriorityAction::class)->handle($lowPriorityCall);

        // Assert a high priority call was created with the same name and duration
        $this->assertDatabaseHas('calls', [
            'name' => $lowPriorityCall->name,
            'priority' => CallPriority::HIGH,
            'duration' => $lowPriorityCall->duration,
        ]);
    }

    public function testEmployeeFreedAfterTheChange()
    {
        // Create a low priority call
        $lowPriorityCall = Call::create([
            'name' => fake()->firstName(),
            'duration' => 3,
            'priority' => CallPriority::LOW
        ]);

        // Create an Employee to handle the call
        $employee = Employee::create([
            'name' => fake()->firstName(),
            'level' => EmployeeLevel::JUNIOR,
            'call_id' => $lowPriorityCall->id
        ]);

        // Execute the action
        app(ChangePriorityAction::class)->handle($lowPriorityCall);

        // Assert the low priority call was deleted
        $this->assertDatabaseMissing('calls',$lowPriorityCall->toArray());

        // Assert a high priority call was created with the same name and duration
        $this->assertDatabaseHas('calls', [
            'name' => $lowPriorityCall->name,
            'priority' => CallPriority::HIGH,
            'duration' => $lowPriorityCall->duration,
        ]);

        // Assert The Employee is free after the change
        $this->assertDatabaseHas('employees', [
            'id' => $employee->id,
            'name' => $employee->name,
            'level' => EmployeeLevel::JUNIOR,
            'call_id' =>null,
        ]);
    }
}

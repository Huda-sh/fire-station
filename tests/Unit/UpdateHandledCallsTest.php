<?php

namespace Tests\Unit;

use App\Actions\CallChanges\UpdateCallsAction;
use App\Enums\CallPriority;
use App\Enums\EmployeeLevel;
use App\Models\Call;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateHandledCallsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * test to check if the value of the duration is being decremented
     */
    public function testDurationDecremented(): void
    {
        $call = Call::create([
            'name' => fake()->firstName(),
            'priority' => CallPriority::LOW,
            'duration' => 3
        ]);
        $employee = Employee::create([
            'name' => fake()->firstName(),
            'level' => EmployeeLevel::JUNIOR,
            'call_id' => $call->id
        ]);
        UpdateCallsAction::run();

        $this->assertDatabaseHas('calls', [
            'id' => $call->id,
            'name' => $call->name,
            'priority' => $call->priority,
            'duration' => 2
        ]);
    }

    /**
     * A basic unit test example.
     */
    public function testCallRemovedWhenCallReachEnd(): void
    {
        $call = Call::create([
            'name' => fake()->firstName(),
            'priority' => CallPriority::LOW,
            'duration' => 1
        ]);
        $employee = Employee::create([
            'name' => fake()->firstName(),
            'level' => EmployeeLevel::JUNIOR,
            'call_id' => $call->id
        ]);

        UpdateCallsAction::run();

        $this->assertDatabaseMissing('calls', [
            'id' => $call->id,
            'name' => $call->name,
            'priority' => $call->priority,
            'duration' => 2
        ]);
    }

    /**
     * A basic unit test example.
     */
    public function testEmployeeFreedWhenCallFinish(): void
    {
        $call = Call::create([
            'name' => fake()->firstName(),
            'priority' => CallPriority::LOW,
            'duration' => 1
        ]);
        $employee = Employee::create([
            'name' => fake()->firstName(),
            'level' => EmployeeLevel::JUNIOR,
            'call_id' => $call->id
        ]);
        UpdateCallsAction::run();

        $this->assertDatabaseHas('employees', [
            'id' => $employee->id,
            'name' => $employee->name,
            'level' => $employee->level,
            'call_id' => null,
        ]);
    }
}

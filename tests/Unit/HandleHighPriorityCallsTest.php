<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HandleHighPriorityCallsTest extends TestCase
{
    use RefreshDatabase;

    /**
     *  manager should take the call
     *  director should take the call
     *  the call should be on hold
     *  all free employees should take call if there is a call available to them
     */

    public function testManagerShouldTakeTheCall()
    {
        $manager = \App\Models\Employee::create([
            'name' => fake()->firstName(),
            'level' => \App\Enums\EmployeeLevel::MANAGER,
        ]);
        $manager2 = \App\Models\Employee::create([
            'name' => fake()->firstName(),
            'level' => \App\Enums\EmployeeLevel::MANAGER,
        ]);

        $Director = \App\Models\Employee::create([
            'name' => fake()->firstName(),
            'level' => \App\Enums\EmployeeLevel::DIRECTOR,
        ]);
        $Director2 = \App\Models\Employee::create([
            'name' => fake()->firstName(),
            'level' => \App\Enums\EmployeeLevel::DIRECTOR,
        ]);

        $call = \App\Models\Call::create([
            'name'=>fake()->firstName(),
            'priority' => \App\Enums\CallPriority::HIGH,
            'duration' => 2,
        ]);

        \App\Actions\CallChanges\HandleHighPriorityCallAction::run();

        $this->assertDatabaseHas('employees',[
            'level'=>\App\Enums\EmployeeLevel::MANAGER,
            'call_id' => $call->id
        ]);
    }
    public function testDirectorShouldTakeTheCallWhenManagerUnavailable()
    {
        $call = \App\Models\Call::create([
            'name'=>fake()->firstName(),
            'priority' => \App\Enums\CallPriority::HIGH,
            'duration' => 2,
        ]);

        $call2 = \App\Models\Call::create([
            'name'=>fake()->firstName(),
            'priority' => \App\Enums\CallPriority::HIGH,
            'duration' => 2,
        ]);

        $call3 = \App\Models\Call::create([
            'name'=>fake()->firstName(),
            'priority' => \App\Enums\CallPriority::HIGH,
            'duration' => 2,
        ]);

        $manager = \App\Models\Employee::create([
            'name' => fake()->firstName(),
            'level' => \App\Enums\EmployeeLevel::MANAGER,
            'call_id' => $call->id
        ]);
        $manager2 = \App\Models\Employee::create([
            'name' => fake()->firstName(),
            'level' => \App\Enums\EmployeeLevel::MANAGER,
            'call_id' => $call2->id
        ]);

        $Director = \App\Models\Employee::create([
            'name' => fake()->firstName(),
            'level' => \App\Enums\EmployeeLevel::DIRECTOR,
        ]);
        $Director2 = \App\Models\Employee::create([
            'name' => fake()->firstName(),
            'level' => \App\Enums\EmployeeLevel::DIRECTOR,
        ]);

        \App\Actions\CallChanges\HandleHighPriorityCallAction::run();

        $this->assertDatabaseHas('employees',[
            'level'=>\App\Enums\EmployeeLevel::DIRECTOR,
            'call_id' => $call3->id
        ]);
    }
    public function testCallOnHoldWhenNoOneIsFree()
    {
        $call = \App\Models\Call::create([
            'name'=>fake()->firstName(),
            'priority' => \App\Enums\CallPriority::HIGH,
            'duration' => 2,
        ]);

        $call2 = \App\Models\Call::create([
            'name'=>fake()->firstName(),
            'priority' => \App\Enums\CallPriority::HIGH,
            'duration' => 2,
        ]);

        $call3 = \App\Models\Call::create([
            'name'=>fake()->firstName(),
            'priority' => \App\Enums\CallPriority::HIGH,
            'duration' => 2,
        ]);

        $manager = \App\Models\Employee::create([
            'name' => fake()->firstName(),
            'level' => \App\Enums\EmployeeLevel::MANAGER,
            'call_id' => $call->id
        ]);

        $Director = \App\Models\Employee::create([
            'name' => fake()->firstName(),
            'level' => \App\Enums\EmployeeLevel::DIRECTOR,
            'call_id' => $call2->id
        ]);

        \App\Actions\CallChanges\HandleHighPriorityCallAction::run();

        $this->assertDatabaseHas('calls',[
            'id' => $call3->id
        ]);
        $this->assertDatabaseMissing('employees',[
            'call_id' => $call3->id
        ]);

    }
    public function testAllFreeEmployeesShouldTakeACallIfCallIsStillAvailable()
    {
        $call = \App\Models\Call::create([
            'name'=>fake()->firstName(),
            'priority' => \App\Enums\CallPriority::HIGH,
            'duration' => 2,
        ]);

        $call2 = \App\Models\Call::create([
            'name'=>fake()->firstName(),
            'priority' => \App\Enums\CallPriority::HIGH,
            'duration' => 2,
        ]);

        $call3 = \App\Models\Call::create([
            'name'=>fake()->firstName(),
            'priority' => \App\Enums\CallPriority::HIGH,
            'duration' => 2,
        ]);
        $call4 = \App\Models\Call::create([
            'name'=>fake()->firstName(),
            'priority' => \App\Enums\CallPriority::HIGH,
            'duration' => 2,
        ]);
        $call5 = \App\Models\Call::create([
            'name'=>fake()->firstName(),
            'priority' => \App\Enums\CallPriority::HIGH,
            'duration' => 2,
        ]);

        $manager = \App\Models\Employee::create([
            'name' => fake()->firstName(),
            'level' => \App\Enums\EmployeeLevel::MANAGER,
        ]);
        $manager2 = \App\Models\Employee::create([
            'name' => fake()->firstName(),
            'level' => \App\Enums\EmployeeLevel::MANAGER,
        ]);

        $Director = \App\Models\Employee::create([
            'name' => fake()->firstName(),
            'level' => \App\Enums\EmployeeLevel::DIRECTOR,
        ]);
        $Director2 = \App\Models\Employee::create([
            'name' => fake()->firstName(),
            'level' => \App\Enums\EmployeeLevel::DIRECTOR,
        ]);

        \App\Actions\CallChanges\HandleHighPriorityCallAction::run();

        $this->assertDatabaseHas('employees',[
            'id' => $manager->id,
            'call_id' => $call->id
        ]);

        $this->assertDatabaseHas('employees',[
            'id' => $manager2->id,
            'call_id' => $call2->id
        ]);
        $this->assertDatabaseHas('employees',[
            'id' => $Director->id,
            'call_id' => $call3->id
        ]);
        $this->assertDatabaseHas('employees',[
            'id' => $Director2->id,
            'call_id' => $call4->id
        ]);
    }
}

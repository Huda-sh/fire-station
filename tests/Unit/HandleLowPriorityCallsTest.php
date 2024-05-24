<?php

class HandleLowPriorityCallsTest extends \Tests\TestCase
{
    use \Illuminate\Foundation\Testing\RefreshDatabase;

    public function testJuniorShouldTakeTheCall()
    {
        $junior = \App\Models\Employee::create([
            'name' => fake()->firstName(),
            'level' => \App\Enums\EmployeeLevel::JUNIOR,
        ]);
        $junior = \App\Models\Employee::create([
            'name' => fake()->firstName(),
            'level' => \App\Enums\EmployeeLevel::JUNIOR,
        ]);
        $senior = \App\Models\Employee::create([
            'name' => fake()->firstName(),
            'level' => \App\Enums\EmployeeLevel::SENIOR,
        ]);
        $senior = \App\Models\Employee::create([
            'name' => fake()->firstName(),
            'level' => \App\Enums\EmployeeLevel::SENIOR,
        ]);
        $manager = \App\Models\Employee::create([
            'name' => fake()->firstName(),
            'level' => \App\Enums\EmployeeLevel::MANAGER,
        ]);
        $manager2 = \App\Models\Employee::create([
            'name' => fake()->firstName(),
            'level' => \App\Enums\EmployeeLevel::MANAGER,
        ]);

        $call = \App\Models\Call::create([
            'name' => fake()->firstName(),
            'priority' => \App\Enums\CallPriority::LOW,
            'duration' => 2,
        ]);

        \App\Actions\CallChanges\HandleLowPriorityCallAction::run();

        $this->assertDatabaseHas('employees', [
            'level' => \App\Enums\EmployeeLevel::JUNIOR,
            'call_id' => $call->id
        ]);
    }

    public function testSeniorShouldTakeTheCallWhenJuniorUnavailable()
    {
        $call = \App\Models\Call::create([
            'name' => fake()->firstName(),
            'priority' => \App\Enums\CallPriority::LOW,
            'duration' => 2,
        ]);

        $call2 = \App\Models\Call::create([
            'name' => fake()->firstName(),
            'priority' => \App\Enums\CallPriority::LOW,
            'duration' => 2,
        ]);


        $junior = \App\Models\Employee::create([
            'name' => fake()->firstName(),
            'level' => \App\Enums\EmployeeLevel::JUNIOR,
            'call_id' => $call->id
        ]);
        $senior = \App\Models\Employee::create([
            'name' => fake()->firstName(),
            'level' => \App\Enums\EmployeeLevel::SENIOR,
        ]);
        $manager = \App\Models\Employee::create([
            'name' => fake()->firstName(),
            'level' => \App\Enums\EmployeeLevel::MANAGER,
        ]);

        \App\Actions\CallChanges\HandleLowPriorityCallAction::run();

        $this->assertDatabaseHas('employees', [
            'level' => \App\Enums\EmployeeLevel::SENIOR,
            'call_id' => $call2->id
        ]);
    }

    public function testManagerShouldTakeTheCallWhenJuniorAndSeniorUnavailable()
    {
        $call = \App\Models\Call::create([
            'name' => fake()->firstName(),
            'priority' => \App\Enums\CallPriority::LOW,
            'duration' => 2,
        ]);

        $call2 = \App\Models\Call::create([
            'name' => fake()->firstName(),
            'priority' => \App\Enums\CallPriority::LOW,
            'duration' => 2,
        ]);

        $call3 = \App\Models\Call::create([
            'name' => fake()->firstName(),
            'priority' => \App\Enums\CallPriority::LOW,
            'duration' => 2,
        ]);


        $junior = \App\Models\Employee::create([
            'name' => fake()->firstName(),
            'level' => \App\Enums\EmployeeLevel::JUNIOR,
            'call_id' => $call->id
        ]);
        $senior = \App\Models\Employee::create([
            'name' => fake()->firstName(),
            'level' => \App\Enums\EmployeeLevel::SENIOR,
            'call_id' => $call2->id
        ]);
        $manager = \App\Models\Employee::create([
            'name' => fake()->firstName(),
            'level' => \App\Enums\EmployeeLevel::MANAGER,
        ]);

        \App\Actions\CallChanges\HandleLowPriorityCallAction::run();

        $this->assertDatabaseHas('employees', [
            'level' => \App\Enums\EmployeeLevel::MANAGER,
            'call_id' => $call3->id
        ]);
    }

    public function testCallOnHoldWhenNoOneIsFree()
    {
        $call = \App\Models\Call::create([
            'name' => fake()->firstName(),
            'priority' => \App\Enums\CallPriority::LOW,
            'duration' => 2,
        ]);

        $call2 = \App\Models\Call::create([
            'name' => fake()->firstName(),
            'priority' => \App\Enums\CallPriority::LOW,
            'duration' => 2,
        ]);

        $call3 = \App\Models\Call::create([
            'name' => fake()->firstName(),
            'priority' => \App\Enums\CallPriority::LOW,
            'duration' => 2,
        ]);

        $call4 = \App\Models\Call::create([
            'name' => fake()->firstName(),
            'priority' => \App\Enums\CallPriority::LOW,
            'duration' => 2,
        ]);

        $junior = \App\Models\Employee::create([
            'name' => fake()->firstName(),
            'level' => \App\Enums\EmployeeLevel::JUNIOR,
            'call_id' => $call->id
        ]);
        $senior = \App\Models\Employee::create([
            'name' => fake()->firstName(),
            'level' => \App\Enums\EmployeeLevel::SENIOR,
            'call_id' => $call2->id
        ]);
        $manager = \App\Models\Employee::create([
            'name' => fake()->firstName(),
            'level' => \App\Enums\EmployeeLevel::MANAGER,
            'call_id' => $call3->id,
        ]);

        \App\Actions\CallChanges\HandleLowPriorityCallAction::run();

        $this->assertDatabaseHas('calls', [
            'id' => $call4->id
        ]);
        $this->assertDatabaseMissing('employees', [
            'call_id' => $call4->id
        ]);

    }

    public function testAllFreeEmployeesShouldTakeACallIfCallIsStillAvailable()
    {
        $call = \App\Models\Call::create([
            'name' => fake()->firstName(),
            'priority' => \App\Enums\CallPriority::LOW,
            'duration' => 2,
        ]);

        $call2 = \App\Models\Call::create([
            'name' => fake()->firstName(),
            'priority' => \App\Enums\CallPriority::LOW,
            'duration' => 2,
        ]);

        $call3 = \App\Models\Call::create([
            'name' => fake()->firstName(),
            'priority' => \App\Enums\CallPriority::LOW,
            'duration' => 2,
        ]);
        $call4 = \App\Models\Call::create([
            'name' => fake()->firstName(),
            'priority' => \App\Enums\CallPriority::LOW,
            'duration' => 2,
        ]);

        $junior = \App\Models\Employee::create([
            'name' => fake()->firstName(),
            'level' => \App\Enums\EmployeeLevel::JUNIOR,
            'call_id' => $call->id
        ]);
        $senior = \App\Models\Employee::create([
            'name' => fake()->firstName(),
            'level' => \App\Enums\EmployeeLevel::SENIOR,
            'call_id' => $call2->id
        ]);
        $manager = \App\Models\Employee::create([
            'name' => fake()->firstName(),
            'level' => \App\Enums\EmployeeLevel::MANAGER,
            'call_id' => $call3->id,
        ]);

        \App\Actions\CallChanges\HandleLowPriorityCallAction::run();

        $this->assertDatabaseHas('employees', [
            'id' => $junior->id,
            'call_id' => $call->id
        ]);

        $this->assertDatabaseHas('employees', [
            'id' => $senior->id,
            'call_id' => $call2->id
        ]);
        $this->assertDatabaseHas('employees', [
            'id' => $manager->id,
            'call_id' => $call3->id
        ]);
    }
}

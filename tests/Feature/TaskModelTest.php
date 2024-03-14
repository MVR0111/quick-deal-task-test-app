<?php

namespace Tests\Feature;

use App\Enums\StatusEnum;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_task()
    {
        $data = [
            'title' => 'Test Task',
            'description' => 'This is a test task',
            'status' => StatusEnum::ToDo,
        ];

        $task = Task::create($data);

        $this->assertInstanceOf(Task::class, $task);
        $this->assertEquals($data['title'], $task->title);
        $this->assertEquals($data['description'], $task->description);
        $this->assertEquals($data['status'], $task->status);
    }
}

<?php

namespace Tests\Feature;

use App\Enums\StatusEnum;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_can_get_tasks(): void
    {
        Task::factory()->count(3)->create();

        $response = $this->getJson('/api/tasks');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_can_filter_tasks_by_status()
    {
        // Создаем задачи с разными статусами
        $todoTask = Task::factory()->create(['status' => StatusEnum::ToDo]);
        $inProgressTask = Task::factory()->create(['status' => StatusEnum::InProgress]);
        $doneTask = Task::factory()->create(['status' => StatusEnum::Done]);

        // Проверяем фильтрацию для "todo" статуса
        $response = $this->getJson('/api/tasks?status=todo');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $todoTask->id);

        // Проверяем фильтрацию для "in_progress" статуса
        $response = $this->getJson('/api/tasks?status=in_progress');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $inProgressTask->id);

        // Проверяем фильтрацию для "done" статуса
        $response = $this->getJson('/api/tasks?status=done');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $doneTask->id);
    }

    public function test_can_get_task_by_id()
    {
        $task = Task::factory()->create();

        $response = $this->getJson("/api/tasks/{$task->id}");

        $response->assertStatus(200)
            ->assertJson(['data' => $task->toArray()]);
    }

    public function test_can_create_task()
    {
        $data = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'status' => StatusEnum::ToDo->value,
        ];

        $response = $this->postJson('/api/tasks', $data);

        $response->assertStatus(201)
            ->assertJson(['data' => $data]);

        $this->assertDatabaseHas('tasks', $data);
    }

    public function test_can_update_task()
    {
        $task = Task::factory()->create();
        $updatedData = [
            'title' => 'Updated Task Title',
            'description' => $task->description,
            'status' => $task->status,
        ];

        $response = $this->putJson("/api/tasks/{$task->id}", $updatedData);

        $response->assertStatus(200)
            ->assertJsonPath('data.title', $updatedData['title']);

        $this->assertDatabaseHas('tasks', $updatedData);
    }

    public function test_can_delete_task()
    {
        $task = Task::factory()->create();

        $response = $this->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Task deleted successfully']);

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }
}

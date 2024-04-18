<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\TaskStatus;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskStatusControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private TaskStatus $taskStatus;
    private array $data;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
        $this->taskStatus = TaskStatus::factory()->create();
        $this->data = ['name' => fake()->word()];
    }

    public function testIndex(): void
    {
        $response = $this->get(route('task_statuses.index'));
        $response->assertOk();
    }

    public function testCreate(): void
    {
        $response = $this->get(route('task_statuses.create'));
        $response->assertOk();
    }

    public function testStore(): void
    {
        $response = $this->post(route('task_statuses.store'), $this->data);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('task_statuses', $this->data);
    }

    public function testEdit(): void
    {
        $response = $this->get(route('task_statuses.edit', ['task_status' => $this->taskStatus]));
        $response->assertOk();
    }

    public function testUpdate(): void
    {
        $response = $this->patch(route('task_statuses.update', ['task_status' => $this->taskStatus]), $this->data);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('task_statuses', $this->data);
    }

    public function testDelete(): void
    {
        $response = $this->delete(route('task_statuses.destroy', ['task_status' => $this->taskStatus]));
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseMissing('task_statuses', ['id' => $this->taskStatus->id]);
    }

    public function testDeleteIfAssociatedWithTask(): void
    {
        Task::factory()->create(['status_id' => $this->taskStatus->id]);
        $response = $this->delete(route('task_statuses.destroy', ['task_status' => $this->taskStatus]));
        $this->assertDatabaseHas('task_statuses', ['id' => $this->taskStatus->id]);
        $response->assertRedirect();
    }
}

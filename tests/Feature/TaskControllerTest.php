<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Task;
use App\Models\TaskStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;
    private User $user;
    private Task $task;
    private array $data;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
        $this->task = Task::factory()->create(['created_by_id' => $this->user->id]);
        $this->data = ['name' => fake()->word(), 'status_id' => TaskStatus::factory()->create()->id];
    }

    public function testIndex(): void
    {
        $response = $this->get(route('tasks.index'));
        $response->assertOk();
    }

    public function testCreate(): void
    {
        $response = $this->get(route('tasks.create'));
        $response->assertOk();
    }

    public function testShow(): void
    {
        $response = $this->get(route('tasks.show', $this->task));
        $response->assertOk();
    }

    public function testStore(): void
    {
        $response = $this->post(route('tasks.store'), $this->data);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('tasks', $this->data);
    }

    public function testEdit(): void
    {
        $response = $this->get(route('tasks.edit', ['task' => $this->task]));
        $response->assertOk();
    }

    public function testUpdate(): void
    {
        $response = $this->patch(route('tasks.update', ['task' => $this->task]), $this->data);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('tasks', $this->data);
    }

    public function testDelete(): void
    {
        $response = $this->delete(route('tasks.destroy', ['task' => $this->task]));
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseMissing('tasks', ['id' => $this->task->id]);
    }
}

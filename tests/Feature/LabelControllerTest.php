<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Label;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LabelControllerTest extends TestCase
{
    use RefreshDatabase;
    private User $user;
    private Label $label;
    private Task $task;
    private array $data;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
        $this->label = Label::factory()->create();
        $this->task = Task::factory()->create();
        $this->data = ['name' => fake()->word()];
    }

    public function testIndex(): void
    {
        $response = $this->get(route('labels.index'));
        $response->assertOk();
    }

    public function testCreate(): void
    {
        $response = $this->get(route('labels.create'));
        $response->assertOk();
    }

    public function testStore(): void
    {
        $response = $this->post(route('labels.store'), $this->data);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('labels', $this->data);
    }

    public function testEdit(): void
    {
        $response = $this->get(route('labels.edit', ['label' => $this->label]));
        $response->assertOk();
    }

    public function testUpdate(): void
    {
        $response = $this->patch(route('labels.update', ['label' => $this->label]), $this->data);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('labels', $this->data);
    }

    public function testDelete(): void
    {
        $response = $this->delete(route('labels.destroy', ['label' => $this->label]));
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseMissing('labels', ['id' => $this->label->id]);
    }

    public function testDeleteIfAssociatedWithTask(): void
    {
        $this->task->labels()->attach(['label' => $this->label->id]);
        $response = $this->delete(route('labels.destroy', ['label' => $this->label]));
        $this->assertDatabaseHas('labels', ['id' => $this->label->id]);
        $response->assertRedirect();
    }
}

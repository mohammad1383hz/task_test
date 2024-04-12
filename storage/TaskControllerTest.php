<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_list_all_tasks()
    {
        $tasks = Task::factory()->count(3)->create();

        $response = $this->getJson(route('tasks.index'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'description',
                    ]
                ]
            ]);
    }

    /** @test */
    public function it_can_show_a_task()
    {
        $task = Task::factory()->create();

        $response = $this->getJson(route('tasks.show', $task));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'data' => [
                    'id' => $task->id,
                    'title' => $task->title,
                    'description' => $task->description,
                    // Add other fields as needed
                ]
            ]);
    }

    /** @test */
    public function it_can_create_a_task()
    {
        $taskData = Task::factory()->raw();

        $response = $this->postJson(route('tasks.store'), $taskData);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJson([
                'data' => [
                    'title' => $taskData['title'],

                    'description' => $taskData['description'],
                    // Add other fields as needed
                ]
            ]);

        $this->assertDatabaseHas('tasks', $taskData);
    }

    /** @test */
    public function it_can_update_a_task()
    {
        $task = Task::factory()->create();
        $updatedTaskData = [
            'title' => 'Updated title',
            'description' => 'Updated Description',
            // Add other fields as needed
        ];

        $response = $this->putJson(route('tasks.update', $task), $updatedTaskData);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'data' => [
                    'title' => 'Updated title',
                    'description' => 'Updated Description',
                    // Add other fields as needed
                ]
            ]);

        $this->assertDatabaseHas('tasks', $updatedTaskData);
    }

    /** @test */
    public function it_can_delete_a_task()
    {
        $task = Task::factory()->create();

        $response = $this->deleteJson(route('tasks.destroy', $task));

        $response->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }
}

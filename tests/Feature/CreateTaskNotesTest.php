<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;

class CreateTaskNotesTest extends TestCase
{
    private User $userModel;

    public function setUp(): void
    {
        parent::setUp();
        $this->userModel = new User();
    }

    public function test_unauthorized_without_token()
    {
        $response = $this->postJson('/api/create-task-notes', []);

        $response->assertStatus(401)
            ->assertJson([
                'error' => 'User is Unauthorized',
            ]);
    }

    public function test_successful_note_creation_with_token()
    {
        $startDate = Carbon::make(now());
        $user = User::factory()->create();

        $data = Task::factory()
            ->state([
                'subject' => 'Subject',
                'description' => 'description',
                'start_date' => $startDate->format('Y-m-d'),
                'due_date' => $startDate->addDays(15)->format('Y-m-d'),
                'status' => 'New',
                'priority' => 'High',
            ])
            ->make()
            ->toArray();

            $data['notes'] = [
                [
                    'subject' => 'Subject',
                    'note' => 'Note Subject',
                ],
            ];
        $response = $this->actingAs($user, 'api')->json('POST', '/api/create-task-notes', $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'message' => 'Task has been created successfully',
            ]);
    }
}

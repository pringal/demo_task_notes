<?php
namespace Tests\Unit\Models;

use App\Models\Note;
use App\Models\NoteAttachments;
use App\Models\Task;
use App\Models\User;
use Tests\TestCase;

class TaskTest extends TestCase
{

    private Task $taskModel;
    private User $userModel;
    private Note $noteModel;
    private NoteAttachments $noteAttachmentsModel;

    public function setUp(): void
    {
        parent::setUp();
        $this->taskModel = new Task();
        $this->userModel = new User();
        $this->noteModel = new Note();
        $this->noteAttachmentsModel = new NoteAttachments();
    }

    /**
    * A basic test example.
    *
    * @return void
    */

    public function testExample()
    {
        // Create an authenticated user
        $user = $this->userModel::factory()->create();
        $this->actingAs($user);

        // Create a task
        $task = $this->taskModel::factory()->create([
            'user_id' => $user->id,
            'subject' => 'Subject',
            'description' => 'description',
            'start_date' => '2024-03-02',
            'due_date' => '2024-03-02',
            'status' => 'New',
            'priority' => 'High',
        ]);

        //create a note
        $notes = $this->noteModel::factory(2)->create([
            'task_id' => $task->id,
            'subject' => 'Subject',
            'note' => 'note',
        ]);

        //Add attachments
        foreach ($notes as $note) {
            $attachments = $this->noteAttachmentsModel::factory(1)->create([
                'note_id' => $note->id,
                'attachment_path' => 'test1.png',
            ]);

            // Assertions for each note and attachments
            $this->assertEquals(1, $note->noteAttachments()->count());
            $this->assertEquals(1, $attachments->count());
        }

        // Assertions
        $this->assertEquals($user->id, $task->user_id);
        $this->assertEquals('Subject', $task->subject);
        $this->assertEquals(2, $notes->count());

    }
}

<?php

namespace Database\Factories;

use App\Models\Note;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class NoteAttachmentsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
         if (!Storage::disk('public')->exists('uploads')) {
            Storage::disk('public')->makeDirectory('uploads');
        }

        if (!Storage::disk('public')->exists('files')) {
            Storage::disk('public')->makeDirectory('files');
        }
        return [
            'note_id' => Note::factory(),
            'attachment_path' => $this->faker->file(storage_path('app/uploads'), storage_path('app/public/files'), false),
        ];
    }
}

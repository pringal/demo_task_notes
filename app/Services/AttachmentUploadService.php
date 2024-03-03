<?php

namespace App\Services;

use App\Models\NoteAttachments;

class AttachmentUploadService
{
    public function uploadNoteAttachments(array $attachments, $noteId)
    {
        foreach ($attachments as $attachment) {
            $path = $attachment->store('uploads', 'public');
            $baseName = basename($path);
            NoteAttachments::create([
                'note_id' => $noteId,
                'attachment_path' => $baseName,
            ]);
        }
    }
}

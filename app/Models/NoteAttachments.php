<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class NoteAttachments extends Model
{
    use HasFactory;

    protected $fillable = ['note_id','attachment_path'];

    /**
     * The "booted" method of the model.
     * @return void
     */
    protected static function booted()
    {
        static::deleting(function ($file) {
            Storage::disk('public')->delete('uploads/' . $file->attachment_path);
        });
    }

    public function getAttachmentPath()
    {
        return Storage::disk('public')->url('uploads/' . $this->attachment_path);
    }
}

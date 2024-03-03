<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $fillable = ['task_id','subject', 'note'];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::deleting(function ($note) {
            foreach ($note->noteAttachments as $file) {
                $file->delete();
            }
        });
    }

    public function noteAttachments()
    {
        return $this->hasMany(NoteAttachments::class);
    }
}

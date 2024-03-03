<?php

namespace App\Models;

use App\Enums\PriorityEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','subject', 'description', 'status', 'priority', 'start_date', 'due_date'];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::deleting(function ($task) {
            $task->load('notes.noteAttachments');

            foreach ($task->notes as $note) {
                foreach ($note->noteAttachments as $file) {
                    $file->delete();
                }
                $note->delete();
            }
        });
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    public function scopeSearch(Builder $query, Request $request)
    {
        if ($request->has('order') && $request->get('order') === 'priority') {
            $priorities = array_column(PriorityEnum::cases(), 'value');
            if ($request->get('order_direction') === 'desc') {
                $priorities = array_reverse($priorities);
            }
            $priorityString = implode('","', $priorities);
            $query->orderByRaw('FIELD(priority, "' . $priorityString . '")');

        } else if ($request->has('order') && $request->get('order') === 'notes_count') {
            $query->orderBy('notes_count', $request->get('order_direction', 'asc'));
        }

        if ($request->has('filter.status') && $request->input('filter.status')) {
            $query->where('status', $request->input('filter.status'));
        }

        if ($request->has('filter.priority') && $request->input('filter.priority')) {
            $query->where('priority', $request->input('filter.priority'));
        }

        if ($request->has('filter.due_date') && $request->input('filter.due_date')) {
            $query->where('due_date', $request->input('filter.due_date'));
        }

        if ($request->has('filter.notes') && $request->input('filter.notes')) {
            $query->having('notes_count', '>=', $request->input('filter.notes'));
        }else{
            $query->having('notes_count', '>=', 1); //At least one note should have in each task fitler
        }

        return $query;
    }
}

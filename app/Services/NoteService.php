<?php

namespace App\Services;

use App\Models\Note;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class NoteService
{
    /**
     * Retrieve notes for the given task with eager loading of the task and pagination
     * 
     * Admin can list any task notes
     * User can list only nots of taks belongs to a project he is working on
     * if he assigned to any task in the same project
     * @return LengthAwarePaginator
     */
    public function listTaskNotes($taskId, User $user)
    {
        try {
            $task = Task::findOrFail($taskId);

            if ($user->isAdmin() || $user->isWorkingInProject($task->project_id)) {

                $notes = Note::with(['task', 'user'])
                    ->where('task_id', $taskId)
                    ->orderBy('created_at', 'desc')
                    ->paginate(5);

                return $notes;
            }

            throw new \Exception('Unauthorized to view these notes.');
        } catch (\Exception $e) {
            Log::error('Failed to retrieve notes: ' . $e->getMessage());
            throw new \Exception('An error occurred on the server.');
        }
    }

    /**
     * Create a new note with the provided data.
     * 
     * @param array $data The validated data to create a note.
     * @return Note|null The created note object on success, or null on failure.
     * @throws \Exception
     */
    public function createNote(array $data): ?Note
    {
        try {
            return Note::create($data);
        } catch (\Exception $e) {
            Log::error('Note creation failed: ' . $e->getMessage());
            throw new \Exception('An error occurred on the server.');
        }
    }

    /**
     * Retrieve the note based on task and note IDs
     * 
     * Admin can show any task notes
     * User can show only nots of taks belongs to a project he is working on
     * if he assigned to any task in the same project
     * 
     * @param Note $note The note object.
     * @return Note|null The retrieved note, or null on failure.
     * @throws \Exception
     */
    public function showNote(int $taskId, int $noteId, User $user): ?Note
    {
        try {
            $task = Task::findOrFail($taskId);

            if ($user->isAdmin() || $user->isWorkingInProject($task->project_id)) {

                $note = Note::where('task_id', $taskId)
                    ->where('id', $noteId)
                    ->firstOrFail();

                return $note;
            }

            throw new \Exception('Unauthorized to view this note.');
        } catch (\Exception $e) {
            Log::error('Failed to retrieve note: ' . $e->getMessage());
            throw new \Exception('An error occurred on the server.');
        }
    }

    /**
     * Update an existing note with the provided data.
     * 
     * @param Note $note The note to update.
     * @param array $data The validated data to update the note.
     * @return Note|null The updated note object on success, or null on failure.
     * @throws \Exception
     */
    public function updateNote(Note $note, array $data): ?Note
    {
        try {
            $note->note = $data['note'] ?? $note->note;
            $note->task_id = $data['task_id'] ?? $note->task_id;
            $note->written_by = $data['written_by'] ?? $note->written_by;
            $note->save();

            return $note;
        } catch (\Exception $e) {
            Log::error('Note update failed: ' . $e->getMessage());
            throw new \Exception('An error occurred on the server.');
        }
    }

    /**
     * Delete a note.
     * 
     * Only admin and thewriter of the note can delete it
     * 
     * @param Note $note The note to delete.
     * @return bool True on success, false on failure.
     * @throws \Exception
     */
    public function deleteNote(Note $note, User $user): bool
    {
        try {
            $task = $note->task;

            if ($user->isAdmin() || $user->hasRoleInTask($task->id, 'tester')) {
                $note->delete();
                return true;
            }

            throw new \Exception('Unauthorized action');
        } catch (ModelNotFoundException $e) {
            Log::error('Failed to delete note: ' . $e->getMessage());
            throw new \Exception('An error occurred on the server.');
        } catch (\Exception $e) {
            Log::error('Unauthorized delete attempt: ' . $e->getMessage());
            throw new \Exception('An error occurred on the server.');
        }
    }
}

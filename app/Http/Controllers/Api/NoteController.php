<?php

namespace App\Http\Controllers\Api;

use App\Models\Note;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\NoteService;
use App\Http\Controllers\Controller;
use App\Http\Resources\NoteResource;
use App\Services\ApiResponseService;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreNoteRequest;
use App\Http\Requests\UpdateNoteRequest;

class NoteController extends Controller
{
    protected $noteService;

    public function __construct(NoteService $noteService)
    {
        $this->noteService = $noteService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index($taskId)
    {
        $user = User::find(Auth::id());

        try {
            $notes = $this->noteService->listTaskNotes($taskId, $user);

            return ApiResponseService::success(NoteResource::collection($notes), 'Notes retrieved successfully', 200);
        } catch (\Exception $e) {
            return ApiResponseService::error('An error occurred on the server.', 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNoteRequest $request)
    {
        $validated = $request->validated();

        try {
            $note = $this->noteService->createNote($validated);
            return ApiResponseService::success(new NoteResource($note), 'Note created successfully', 201);
        } catch (\Exception $e) {
            return ApiResponseService::error('An error occurred on the server.', 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $taskId, int $noteId)
    {
        $user = User::find(Auth::id());

        try {
            $note = $this->noteService->showNote($taskId, $noteId, $user);
            return ApiResponseService::success(new NoteResource($note), 'Note retrieved successfully', 200);
        } catch (\Exception $e) {
            return ApiResponseService::error('An error occurred on the server.', 500);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNoteRequest $request, Note $note)
    {
        $validated = $request->validated();

        try {
            $updatedNote = $this->noteService->updateNote($note, $validated);
            return ApiResponseService::success(new NoteResource($updatedNote), 'Note updated successfully', 200);
        } catch (\Exception $e) {
            return ApiResponseService::error('An error occurred on the server.', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Note $note)
    {
        try {
            $user = User::find(Auth::id());
            $deleted = $this->noteService->deleteNote($note, $user);

            if ($deleted) {
                return ApiResponseService::success(null, 'Note deleted successfully', 200);
            } else {
                return ApiResponseService::error('Failed to delete the note.', 400);
            }
        } catch (\Exception $e) {
            return ApiResponseService::error('An error occurred on the server: ' . $e->getMessage(), 500);
        }
    }
}

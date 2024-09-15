<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Services\TaskService;
use App\Http\Resources\TaskResource;
use App\Services\ApiResponseService;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;

class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    /**
     * Display a listing of the tasks.
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $tasks = $this->taskService->getAllTasks();
            return ApiResponseService::success(TaskResource::collection($tasks), 'Tasks retrieved successfully', 200);
        } catch (\Exception $e) {
            return ApiResponseService::error('An error occurred on the server.', 500);
        }
    }

    /**
     * Store a newly created tasks.
     * 
     * @param StoreTaskRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreTaskRequest $request)
    {
        $validated = $request->validated();

        try {
            $task = $this->taskService->createTask($validated);
            return ApiResponseService::success(new TaskResource($task), 'Task created successfully', 201);
        } catch (\Exception $e) {
            return ApiResponseService::error('An error occurred on the server.', 500);
        }
    }

    /**
     * Display the specified task.
     * 
     * @param Task $project
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Task $task)
    {
        try {
            $task = $this->taskService->showTask($task);
            return ApiResponseService::success(new TaskResource($task), 'Task retrieved successfully', 200);
        } catch (\Exception $e) {
            return ApiResponseService::error('An error occurred on the server.', 500);
        }
    }

    /**
     * Update the specified task.
     * 
     * @param \App\Http\Requests\UpdateTaskRequest $request
     * @param \App\Models\Task $task
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $validated = $request->validated();

        try {
            $updatedTask = $this->taskService->updateTask($task, $validated);
            return ApiResponseService::success(new TaskResource($updatedTask), 'Task updated successfully', 200);
        } catch (\Exception $e) {
            return ApiResponseService::error('An error occurred on the server.', 500);
        }
    }

    /**
     * Remove the specified task.
     * 
     * @param \App\Models\Task $task
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Task $task)
    {
        try {
            $deleted = $this->taskService->deleteTask($task);
            if ($deleted) {
                return ApiResponseService::success(null, 'Task deleted successfully', 200);
            } else {
                return ApiResponseService::error('Failed to delete the task.', 400);
            }
        } catch (\Exception $e) {
            return ApiResponseService::error('An error occurred on the server.', 500);
        }
    }
}

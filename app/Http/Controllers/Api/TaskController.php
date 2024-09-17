<?php

namespace App\Http\Controllers\Api;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\TaskService;
use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Services\ApiResponseService;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Requests\UpdateTaskStatusRequest;

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
    // TaskController.php

    public function index(Request $request)
    {
        try {
            $status = $request->input('status');
            $priority = $request->input('priority');

            $userId = Auth::id();

            $tasks = $this->taskService->getFilteredTasks($userId, $status, $priority);

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
        $user = User::find(Auth::id());

        try {
            $updatedTask = $this->taskService->updateTask($task, $validated, $user);

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

    /**
     * Display the oldest task for a user.
     * 
     * @param int $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function showOldestTaskForUser(int $userId)
    {
        try {
            $task = $this->taskService->getOldestTaskForUser($userId);
            return $task
                ? ApiResponseService::success(new TaskResource($task), 'Oldest task retrieved successfully.', 200)
                : ApiResponseService::error('Task not found.', 404);
        } catch (\Exception $e) {
            return ApiResponseService::error('An error occurred on the server.', 500);
        }
    }

    /**
     * Display the latest task for a user.
     * 
     * @param int $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function showLatestTaskForUser(int $userId)
    {
        try {
            $task = $this->taskService->getLatestTaskForUser($userId);
            return $task
                ? ApiResponseService::success(new TaskResource($task), 'Latest task retrieved successfully.', 200)
                : ApiResponseService::error('Task not found.', 404);
        } catch (\Exception $e) {
            return ApiResponseService::error('An error occurred on the server.', 500);
        }
    }

    /**
     * Display the oldest task for a project.
     * 
     * @param int $projectId
     * @return \Illuminate\Http\JsonResponse
     */
    public function showOldestTaskForProject(int $projectId)
    {
        try {
            $task = $this->taskService->getOldestTaskForProject($projectId);
            return $task
                ? ApiResponseService::success(new TaskResource($task), 'Oldest task retrieved successfully.', 200)
                : ApiResponseService::error('Task not found.', 404);
        } catch (\Exception $e) {
            return ApiResponseService::error('An error occurred on the server.', 500);
        }
    }

    /**
     * Display the latest task for a project.
     * 
     * @param int $projectId
     * @return \Illuminate\Http\JsonResponse
     */
    public function showLatestTaskForProject(int $projectId)
    {
        try {
            $task = $this->taskService->getLatestTaskForProject($projectId);
            return $task
                ? ApiResponseService::success(new TaskResource($task), 'Latest task retrieved successfully.', 200)
                : ApiResponseService::error('Task not found.', 404);
        } catch (\Exception $e) {
            return ApiResponseService::error('An error occurred on the server.', 500);
        }
    }

    /**
     * Display tasks by projects for a given user.
     * 
     * @param int $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function showTasksByUserProjects(int $userId)
    {
        try {
            $tasks = $this->taskService->getTasksByUserProjects($userId);
            return ApiResponseService::success(TaskResource::collection($tasks), 'Tasks retrieved successfully.', 200);
        } catch (\Exception $e) {
            return ApiResponseService::error('An error occurred on the server.', 500);
        }
    }
    /**
     * Update the status of the specified task.
     *
     * @param UpdateTaskStatusRequest $request
     * @param Task $task
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateTaskStatus(UpdateTaskStatusRequest $request, Task $task)
    {
        $validated = $request->validated();

        try {
            $task = $this->taskService->updateTaskStatus($task, $validated);
            return ApiResponseService::success(new TaskResource($task), 'Task status updated successfully', 200);
        } catch (\Exception $e) {
            return ApiResponseService::error('An error occurred on the server.', 500);
        }
    }
}

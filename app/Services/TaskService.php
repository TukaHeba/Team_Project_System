<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TaskService
{
    /**
     * Retrieve all tasks with pagination.
     *
     * @return LengthAwarePaginator
     * @throws \Exception
     */
    public function getAllTasks()
    {
        try {
            return Task::paginate(10);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve tasks: ' . $e->getMessage());
            throw new \Exception('An error occurred on the server.');
        }
    }

    /**
     * Create a new task.
     *
     * @param array $data
     * @return Task|null
     * @throws \Exception
     */
    public function createTask(array $data): ?Task
    {
        try {
            $task = Task::create($data);
            return $task;
        } catch (\Exception $e) {
            Log::error('Task creation failed: ' . $e->getMessage());
            throw new \Exception('An error occurred on the server.');
        }
    }

    /**
     * Retrieve a single task.
     *
     * @param Task $task
     * @return Task|null
     * @throws \Exception
     */
    public function showTask(Task $task): ?Task
    {
        try {
            return $task;
        } catch (\Exception $e) {
            Log::error('Failed to retrieve task: ' . $e->getMessage());
            throw new \Exception('An error occurred on the server.');
        }
    }

    /**
     * Update an existing task.
     *
     * @param Task $task
     * @param array $data
     * @return Task|null
     * @throws \Exception
     */
    public function updateTask(Task $task, array $data): ?Task
    {
        try {
            $task->title = $data['title'] ?? $task->title;
            $task->description = $data['description'] ?? $task->description;
            $task->priority = $data['priority'] ?? $task->priority;
            $task->due_date = $data['due_date'] ?? $task->due_date;
            $task->status = $data['status'] ?? $task->status;
            $task->assigned_to = $data['assigned_to'] ?? $task->assigned_to;
            $task->project_id = $data['project_id'] ?? $task->project_id;
            $task->hours = $data['hours'] ?? $task->hours;
            $task->save();

            return $task;
        } catch (\Exception $e) {
            Log::error('Task update failed: ' . $e->getMessage());
            throw new \Exception('An error occurred on the server.');
        }
    }

    /**
     * Delete a task.
     *
     * @param Task $task
     * @return bool
     * @throws \Exception
     */
    public function deleteTask(Task $task): bool
    {
        try {
            return $task->delete();
        } catch (\Exception $e) {
            Log::error('Failed to delete task: ' . $e->getMessage());
            throw new \Exception('An error occurred on the server.');
        }
    }
}

<?php

namespace App\Services;

use App\Models\Task;
use App\Models\User;
use App\Models\Project;
use App\Models\ProjectUser;
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
     * Get filterd tasks
     * 
     * @param mixed $userId
     * @param mixed $status
     * @param mixed $priority
     * @throws \Exception
     * @return mixed|\Illuminate\Database\Eloquent\Collection
     */
    public function getFilteredTasks($userId, $status = null, $priority = null)
    {
        try {
            $user = User::findOrFail($userId);

            $tasks = $user->filterTasks($status, $priority);

            return $tasks;
        } catch (\Exception $e) {
            Log::error('Failed to retrieve filtered tasks: ' . $e->getMessage());
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
    public function updateTask(Task $task, array $data, User $user): ?Task
    {
        try {
            $isManager = $user->hasRoleInProject($task->project_id, 'manager');

            if (!$isManager) {
                throw new \Exception('Unauthorized action. Only managers can update tasks.');
            }
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

    /**
     * Get the latest task for a project.
     *
     * @param int $projectId
     * @return Task|null
     * @throws \Exception
     */
    public function getLatestTaskForProject(int $projectId): ?Task
    {
        try {
            $project = Project::findOrFail($projectId);
            return $project->latestTask()->first();
        } catch (\Exception $e) {
            Log::error('Failed to retrieve latest task for project: ' . $e->getMessage());
            throw new \Exception('An error occurred on the server.');
        }
    }

    /**
     * Get the oldest task for a project.
     *
     * @param int $projectId
     * @return Task|null
     * @throws \Exception
     */
    public function getOldestTaskForProject(int $projectId): ?Task
    {
        try {
            $project = Project::findOrFail($projectId);
            return $project->oldestTask()->first();
        } catch (\Exception $e) {
            Log::error('Failed to retrieve oldest task for project: ' . $e->getMessage());
            throw new \Exception('An error occurred on the server.');
        }
    }

    /**
     * Get the latest task for a user.
     *
     * @param int $userId
     * @return Task|null
     * @throws \Exception
     */
    public function getLatestTaskForUser(int $userId): ?Task
    {
        try {
            $user = User::findOrFail($userId);
            return $user->latestTask()->first();
        } catch (\Exception $e) {
            Log::error('Failed to retrieve latest task for user: ' . $e->getMessage());
            throw new \Exception('An error occurred on the server.');
        }
    }

    /**
     * Get the oldest task for a user.
     *
     * @param int $userId
     * @return Task|null
     * @throws \Exception
     */
    public function getOldestTaskForUser(int $userId): ?Task
    {
        try {
            $user = User::findOrFail($userId);
            return $user->oldestTask()->first();
        } catch (\Exception $e) {
            Log::error('Failed to retrieve oldest task for user: ' . $e->getMessage());
            throw new \Exception('An error occurred on the server.');
        }
    }

    /**
     * Retrieve all tasks for a given user by projects.
     *
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     * @throws \Exception
     */
    public function getTasksByUserProjects(int $userId)
    {
        try {
            $user = User::findOrFail($userId);
            return $user->tasksByProjects;
        } catch (\Exception $e) {
            Log::error('Failed to retrieve tasks by user projects: ' . $e->getMessage());
            throw new \Exception('An error occurred on the server.');
        }
    }

    /**
     * Update the status of a task.
     *
     * @param Task $task
     * @param string $status
     * @return Task
     * @throws \Exception
     */
    public function updateTaskStatus(Task $task, string $status): Task
    {
        try {
            $task->status = $status;
            $task->save();

            return $task;
        } catch (\Exception $e) {
            Log::error('Failed to update task status: ' . $e->getMessage());
            throw new \Exception('An error occurred on the server.');
        }
    }

    /**
     * Summary of updateContributionData
     * @param int $projectId
     * @param int $userId
     * @throws \Exception
     * @return void
     */
    /**
     * Update pivot data for a given project and user.
     *
     * @param int $projectId
     * @param int $userId
     * @throws \Exception
     * @return void
     */
    public function updatePivotData(int $projectId, int $userId)
    {
        try {
            $projectUser = ProjectUser::where('project_id', $projectId)
                ->where('user_id', $userId)
                ->first();

            if ($projectUser) {
                $projectUser->updateTableData();
            }
        } catch (\Exception $e) {
            Log::error('Failed to update contribution data: ' . $e->getMessage());
            throw new \Exception('An error occurred while updating contribution data.');
        }
    }
}

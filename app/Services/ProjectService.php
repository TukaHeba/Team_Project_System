<?php

namespace App\Services;

use App\Models\Project;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProjectService
{
    /**
     * Retrieve all projects with pagination.
     * 
     * @return LengthAwarePaginator
     */
    public function getAllProjects()
    {
        try {
            return Project::paginate(10);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve projects: ' . $e->getMessage());
            throw new \Exception('An error occurred on the server.');
        }
    }

    /**
     * Create a new project with the provided data.
     * 
     * @param array $data
     * @return Project|null
     */
    public function createProject(array $data): ?Project
    {
        try {
            return Project::create($data);
        } catch (\Exception $e) {
            Log::error('Project creation failed: ' . $e->getMessage());
            throw new \Exception('An error occurred on the server.');
        }
    }

    /**
     * Retrieve a single project.
     * 
     * @param Project $project
     * @return Project|null
     */
    public function showProject(Project $project): ?Project
    {
        try {
            return $project;
        } catch (\Exception $e) {
            Log::error('Failed to retrieve project: ' . $e->getMessage());
            throw new \Exception('An error occurred on the server.');
        }
    }

    /**
     * Update an existing project with the provided data.
     * 
     * @param Project $project
     * @param array $data
     * @return Project|null
     */
    public function updateProject(Project $project, array $data): ?Project
    {
        try {
            $project->name = $data['name'] ?? $project->name;
            $project->description = $data['description'] ?? $project->description;
            $project->save();

            return $project;
        } catch (\Exception $e) {
            Log::error('Project update failed: ' . $e->getMessage());
            throw new \Exception('An error occurred on the server.');
        }
    }

    /**
     * Delete a project.
     * 
     * @param Project $project
     * @return bool
     */
    public function deleteProject(Project $project): bool
    {
        try {
            return $project->delete();
        } catch (\Exception $e) {
            Log::error('Failed to delete project: ' . $e->getMessage());
            throw new \Exception('An error occurred on the server.');
        }
    }
}

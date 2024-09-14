<?php

namespace App\Http\Controllers\Api;

use App\Models\Project;
use App\Services\ProjectService;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use App\Services\ApiResponseService;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;

class ProjectController extends Controller
{
    protected $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    /**
     * Display a listing of the projects.
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $projects = $this->projectService->getAllProjects();
            return ApiResponseService::success(ProjectResource::collection($projects), 'Projects retrieved successfully', 200);
        } catch (\Exception $e) {
            return ApiResponseService::error('An error occurred on the server.', 500);
        }
    }

    /**
     * Store a newly created project.
     * 
     * @param StoreProjectRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreProjectRequest $request)
    {
        $validated = $request->validated();

        try {
            $project = $this->projectService->createProject($validated);
            return ApiResponseService::success(new ProjectResource($project), 'Project created successfully', 201);
        } catch (\Exception $e) {
            return ApiResponseService::error('An error occurred on the server.', 500);
        }
    }

    /**
     * Display the specified project.
     * 
     * @param Project $project
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Project $project)
    {
        try {
            $project = $this->projectService->showProject($project);
            return ApiResponseService::success(new ProjectResource($project), 'Project retrieved successfully', 200);
        } catch (\Exception $e) {
            return ApiResponseService::error('An error occurred on the server.', 500);
        }
    }

    /**
     * Update the specified project.
     * 
     * @param UpdateProjectRequest $request
     * @param Project $project
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        $validated = $request->validated();

        try {
            $updatedProject = $this->projectService->updateProject($project, $validated);
            return ApiResponseService::success(new ProjectResource($updatedProject), 'Project updated successfully', 200);
        } catch (\Exception $e) {
            return ApiResponseService::error('An error occurred on the server.', 500);
        }
    }

    /**
     * Remove the specified project.
     * 
     * @param Project $project
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Project $project)
    {
        try {
            $deleted = $this->projectService->deleteProject($project);

            if ($deleted) {
                return ApiResponseService::success(null, 'Project deleted successfully', 200);
            } else {
                return ApiResponseService::error('Failed to delete the project.', 400);
            }
        } catch (\Exception $e) {
            return ApiResponseService::error('An error occurred on the server.', 500);
        }
    }
}

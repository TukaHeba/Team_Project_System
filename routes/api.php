<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\NoteController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Auth Routes
Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout')->middleware('auth:api');
    Route::post('refresh', 'refresh')->middleware('auth:api');
});

// User Routes
Route::apiResource('users', UserController::class)->middleware(['auth:api', 'admin']);

// Project Routes
Route::apiResource('projects', ProjectController::class)->middleware(['auth:api', 'admin']);

// Task Routes
// Route::apiResource('tasks', TaskController::class)->middleware(['auth:api', 'admin']);

// Note Routes
Route::controller(NoteController::class)->group(function () {
    Route::get('tasks/{taskId}/notes', 'index')->middleware('auth:api');
    Route::post('tasks/{taskId}/notes', 'store')->middleware('auth:api');
    Route::get('tasks/{taskId}/notes/{noteId}', 'show')->middleware('auth:api');
    Route::put('tasks/{taskId}/notes/{noteId}', 'update')->middleware('auth:api');
    Route::delete('tasks/{taskId}/notes/{noteId}', 'destroy')->middleware('auth:api');
});

// Task Routes
Route::controller(TaskController::class)->group(function () {

    // Task CRUD Routes with middleware
    Route::apiResource('tasks', TaskController::class)->middleware('auth:api');

    // Get the latest task for a project
    Route::get('/projects/{projectId}/latest-task', 'getLatestTaskForProject')->middleware('auth:api');

    // Get the oldest task for a project
    Route::get('/projects/{projectId}/oldest-task', 'getOldestTaskForProject')->middleware('auth:api');

    // Get the latest task for a user
    Route::get('/users/{userId}/latest-task', 'getLatestTaskForUser')->middleware('auth:api');

    // Get the oldest task for a user
    Route::get('/users/{userId}/oldest-task', 'getOldestTaskForUser')->middleware('auth:api');

    // Retrieve all tasks for a given user by projects
    Route::get('/users/{userId}/tasks-by-projects', 'getTasksByUserProjects')->middleware('auth:api');

    // Update the status of a task
    Route::patch('/tasks/{task}/status', 'updateTaskStatus')->middleware('auth:api');
});

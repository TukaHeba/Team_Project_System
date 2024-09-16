<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Task::create([
            'title' => 'Task 1',
            'description' => 'Description of task 1',
            'priority' => 'high',
            'due_date' => '19-09-2024 00:00',
            'status' => 'in_progress',
            'assigned_to' => 2,
            'project_id' => 1,
            'hours' => 5,
        ]);
        Task::create([
            'title' => 'Task 2',
            'description' => 'Description of task 2',
            'priority' => 'high',
            'due_date' => '19-09-2024 00:00',
            'status' => 'in_progress',
            'assigned_to' => 2,
            'project_id' => 2,
            'hours' => 15,
        ]);
        Task::create([
            'title' => 'Task 3',
            'description' => 'Description of task 3',
            'priority' => 'high',
            'due_date' => '19-09-2024 00:00',
            'status' => 'new',
            'assigned_to' => 3,
            'project_id' => 4,
            'hours' => 11,
        ]);
        Task::create([
            'title' => 'Task 4',
            'description' => 'Description of task 4',
            'priority' => 'high',
            'due_date' => '19-09-2024 00:00',
            'status' => 'new',
            'assigned_to' => 4,
            'project_id' => 3,
            'hours' => 5,
        ]);
        Task::create([
            'title' => 'Task 5',
            'description' => 'Description of task 5',
            'priority' => 'medium',
            'due_date' => '22-09-2024 00:00',
            'status' => 'in_progress',
            'assigned_to' => 6,
            'project_id' => 2,
            'hours' => 7,
        ]);
        Task::create([
            'title' => 'Task 6',
            'description' => 'Description of task 6',
            'priority' => 'low',
            'due_date' => '22-09-2024 00:00',
            'status' => 'new',
            'assigned_to' => 6,
            'project_id' => 7,
            'hours' => 17,
        ]);
        Task::create([
            'title' => 'Task 7',
            'description' => 'Description of task 7',
            'priority' => 'low',
            'due_date' => '22-09-2024 00:00',
            'status' => 'new',
            'assigned_to' => 7,
            'project_id' => 7,
            'hours' => 13,
        ]);
        Task::create([
            'title' => 'Task 8',
            'description' => 'Description of task 8',
            'priority' => 'low',
            'due_date' => '22-09-2024 00:00',
            'status' => 'new',
            'assigned_to' => 7,
            'project_id' => 5,
            'hours' => 4,
        ]);
        Task::create([
            'title' => 'Task 9',
            'description' => 'Description of task 9',
            'priority' => 'low',
            'due_date' => '22-09-2024 00:00',
            'status' => 'new',
            'assigned_to' => 5,
            'project_id' => 7,
            'hours' => 11,
        ]);
        Task::create([
            'title' => 'Task 10',
            'description' => 'Description of task 10',
            'priority' => 'low',
            'due_date' => '22-09-2024 00:00',
            'status' => 'new',
            'assigned_to' => null,
            'project_id' => 7,
            'hours' => 3,
        ]);
        Task::create([
            'title' => 'Task 11',
            'description' => 'Description of task 11',
            'priority' => 'low',
            'due_date' => '22-09-2024 00:00',
            'status' => 'new',
            'assigned_to' => 3,
            'project_id' => 3,
            'hours' => 3,
        ]);
        Task::create([
            'title' => 'Task 12',
            'description' => 'Description of task 12',
            'priority' => 'low',
            'due_date' => '22-09-2024 00:00',
            'status' => 'new',
            'assigned_to' => 4,
            'project_id' => 5,
            'hours' => 3,
        ]);
        Task::create([
            'title' => 'Task 13',
            'description' => 'Description of task 13',
            'priority' => 'low',
            'due_date' => '22-09-2024 00:00',
            'status' => 'new',
            'assigned_to' => 5,
            'project_id' => 6,
            'hours' => 3,
        ]);
        Task::create([
            'title' => 'Task 14',
            'description' => 'Description of task 14',
            'priority' => 'low',
            'due_date' => '22-09-2024 00:00',
            'status' => 'new',
            'assigned_to' => null,
            'project_id' => 8,
            'hours' => 3,
        ]);
        Task::create([
            'title' => 'Task 15',
            'description' => 'Description of task 15',
            'priority' => 'low',
            'due_date' => '22-09-2024 00:00',
            'status' => 'new',
            'assigned_to' => null,
            'project_id' => 9,
            'hours' => 3,
        ]);
        Task::create([
            'title' => 'Task 16',
            'description' => 'Description of task 16',
            'priority' => 'low',
            'due_date' => '22-09-2024 00:00',
            'status' => 'new',
            'assigned_to' => null,
            'project_id' => 10,
            'hours' => 3,
        ]);
    }
}

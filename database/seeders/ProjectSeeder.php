<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Project::create([
            'name' => 'Project 1',
            'description' => 'This is the description of the project 1',
        ]);
        Project::create([
            'name' => 'Project 2',
            'description' => 'This is the description of the project 2',
        ]);
        Project::create([
            'name' => 'Project 3',
            'description' => 'This is the description of the project 3',
        ]);
        Project::create([
            'name' => 'Project 4',
            'description' => 'This is the description of the project 4',
        ]);
        Project::create([
            'name' => 'Project 5',
            'description' => 'This is the description of the project 5',
        ]);
        Project::create([
            'name' => 'Project 6',
            'description' => 'This is the description of the project 6',
        ]);
        Project::create([
            'name' => 'Project 7',
            'description' => 'This is the description of the project 7',
        ]);
        Project::create([
            'name' => 'Project 8',
            'description' => 'This is the description of the project 8',
        ]);
        Project::create([
            'name' => 'Project 9',
            'description' => 'This is the description of the project 9',
        ]);
        Project::create([
            'name' => 'Project 10',
            'description' => 'This is the description of the project 10',
        ]);
    }
}

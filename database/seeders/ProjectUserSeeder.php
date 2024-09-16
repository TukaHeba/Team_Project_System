<?php

namespace Database\Seeders;

use App\Models\ProjectUser;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProjectUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProjectUser::create([
            'project_id' => 1,
            'user_id' => 6,
            'role' => 'tester',
            'contribution_hours' => 20,
            'last_activity' => now(),
        ]);

        ProjectUser::create([
            'project_id' => 1,
            'user_id' => 2,
            'role' => 'manager',
            'contribution_hours' => 15,
            'last_activity' => now(),
        ]);

        ProjectUser::create([
            'project_id' => 2,
            'user_id' => 3,
            'role' => 'manager',
            'contribution_hours' => 10,
            'last_activity' => now(),
        ]);

        ProjectUser::create([
            'project_id' => 3,
            'user_id' => 4,
            'role' => 'developer',
            'contribution_hours' => 30,
            'last_activity' => now(),
        ]);

        ProjectUser::create([
            'project_id' => 4,
            'user_id' => 5,
            'role' => 'developer',
            'contribution_hours' => 25,
            'last_activity' => now(),
        ]);

        ProjectUser::create([
            'project_id' => 5,
            'user_id' => 6,
            'role' => 'tester',
            'contribution_hours' => 20,
            'last_activity' => now(),
        ]);

        ProjectUser::create([
            'project_id' => 6,
            'user_id' => 7,
            'role' => 'tester',
            'contribution_hours' => 15,
            'last_activity' => now(),
        ]);
    }
}

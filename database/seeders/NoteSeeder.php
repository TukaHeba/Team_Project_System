<?php

namespace Database\Seeders;

use App\Models\Note;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Note::create([
            'note' => 'This is the note 1 in task 5',
            'task_id' => 5,
            'written_by' => 6,
        ]);
        Note::create([
            'note' => 'This is the note 2 in task 5',
            'task_id' => 5,
            'written_by' => 6,
        ]);
        Note::create([
            'note' => 'This is the note 1 in task 6',
            'task_id' => 6,
            'written_by' => 6,
        ]);
        Note::create([
            'note' => 'This is the note 2 in task 6',
            'task_id' => 6,
            'written_by' => 6,
        ]);

        Note::create([
            'note' => 'This is the note 1 in task 7',
            'task_id' => 7,
            'written_by' => 7,
        ]);
        Note::create([
            'note' => 'This is the note 2 in task 7',
            'task_id' => 7,
            'written_by' => 7,
        ]);
        Note::create([
            'note' => 'This is the note 1 in task 8',
            'task_id' => 8,
            'written_by' => 7,
        ]);
        Note::create([
            'note' => 'This is the note 2 in task 8',
            'task_id' => 8,
            'written_by' => 7,
        ]);
    }
}

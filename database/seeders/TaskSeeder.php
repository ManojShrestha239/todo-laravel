<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample tasks
        \App\Models\Task::factory(15)->create();

        // Create some completed tasks
        \App\Models\Task::factory(5)->completed()->create();

        // Create some overdue tasks
        \App\Models\Task::factory(3)->overdue()->create();

        // Create some specific example tasks
        \App\Models\Task::create([
            'title' => 'Welcome to your To-Do List!',
            'description' => 'This is a sample task to help you get started. You can edit, complete, or delete this task.',
            'due_date' => now()->addDays(1)->format('Y-m-d'),
            'completed' => false,
        ]);

        \App\Models\Task::create([
            'title' => 'Plan weekend activities',
            'description' => 'Research local events, make dinner reservations, and check weather forecast.',
            'due_date' => now()->addDays(5)->format('Y-m-d'),
            'completed' => false,
        ]);

        \App\Models\Task::create([
            'title' => 'Review project documentation',
            'description' => 'Go through all project docs and update any outdated information.',
            'due_date' => null,
            'completed' => true,
        ]);
    }
}

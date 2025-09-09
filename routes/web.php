<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

// Redirect root to tasks
Route::get('/', function () {
    return redirect()->route('tasks.index');
});

// Task resource routes
Route::resource('tasks', TaskController::class);

// Additional task routes
Route::patch('tasks/{task}/toggle', [TaskController::class, 'toggle'])->name('tasks.toggle');
Route::get('tasks-data', [TaskController::class, 'getTasksData'])->name('tasks.data');
Route::get('trash', [TaskController::class, 'trash'])->name('tasks.trash');
Route::patch('trash/{id}/restore', [TaskController::class, 'restore'])->name('tasks.restore');
Route::delete('trash/{id}/force-delete', [TaskController::class, 'forceDelete'])->name('tasks.force-delete');

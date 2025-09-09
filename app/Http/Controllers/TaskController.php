<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Http\Requests\TaskRequest;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Task::orderBy('created_at', 'desc')->orderByDueDate();

        // Search functionality
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Filter functionality
        if ($request->filled('status')) {
            if ($request->status === 'completed') {
                $query->completed();
            } elseif ($request->status === 'pending') {
                $query->pending();
            }
        }

        if ($request->filled('date_filter')) {
            switch ($request->date_filter) {
                case 'today':
                    $query->whereDate('due_date', today());
                    break;
                case 'this_week':
                    $query->whereBetween('due_date', [
                        now()->startOfWeek(),
                        now()->endOfWeek()
                    ]);
                    break;
                case 'overdue':
                    $query->where('due_date', '<', today())
                        ->where('completed', false);
                    break;
            }
        }

        // return $tasks = $query->latest()->get();
        // return $tasks = $query->where('created_at', 'asc')->get();
        $tasks = $query->get();

        $pendingTasks = $tasks->where('completed', false);
        $completedTasks = $tasks->where('completed', true);

        return view('tasks.index', compact('pendingTasks', 'completedTasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tasks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskRequest $request)
    {

        $task = Task::create($request->validated());

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Task created successfully!',
                'task' => $task
            ], 201);
        }

        return redirect()->route('tasks.index')
            ->with('success', 'Task created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        return view('tasks.edit', compact('task'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskRequest $request, Task $task)
    {
        $task->update($request->validated());

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Task updated successfully!',
                'task' => $task->fresh()
            ]);
        }

        return redirect()->route('tasks.index')
            ->with('success', 'Task updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Task moved to trash!'
            ]);
        }

        return redirect()->route('tasks.index')
            ->with('success', 'Task moved to trash!');
    }

    /**
     * Toggle task completion status.
     */
    public function toggle(Task $task)
    {
        $task->update(['completed' => !$task->completed]);

        $message = $task->completed ? 'Task marked as completed!' : 'Task marked as pending!';

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'task' => $task->fresh()
            ]);
        }

        return redirect()->route('tasks.index')
            ->with('success', $message);
    }

    /**
     * Get tasks data for AJAX requests.
     */
    public function getTasksData(Request $request)
    {
        $query = Task::orderBy('created_at', 'desc')->orderByDueDate();

        // Search functionality
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Filter functionality
        if ($request->filled('status')) {
            if ($request->status === 'completed') {
                $query->completed();
            } elseif ($request->status === 'pending') {
                $query->pending();
            }
        }

        if ($request->filled('date_filter')) {
            switch ($request->date_filter) {
                case 'today':
                    $query->whereDate('due_date', today());
                    break;
                case 'this_week':
                    $query->whereBetween('due_date', [
                        now()->startOfWeek(),
                        now()->endOfWeek()
                    ]);
                    break;
                case 'overdue':
                    $query->where('due_date', '<', today())
                        ->where('completed', false);
                    break;
            }
        }

        $tasks = $query->get();
        $pendingTasks = $tasks->where('completed', false);
        $completedTasks = $tasks->where('completed', true);

        return response()->json([
            'pendingTasks' => $pendingTasks->values(),
            'completedTasks' => $completedTasks->values(),
            'statistics' => [
                'total' => $tasks->count(),
                'pending' => $pendingTasks->count(),
                'completed' => $completedTasks->count(),
                'overdue' => $pendingTasks->filter(fn($task) => $task->isOverdue())->count()
            ]
        ]);
    }

    /**
     * Display trashed tasks.
     */
    public function trash()
    {
        $trashedTasks = Task::onlyTrashed()->orderByDueDate()->get();

        return view('tasks.trash', compact('trashedTasks'));
    }

    /**
     * Restore a trashed task.
     */
    public function restore($id)
    {
        $task = Task::withTrashed()->findOrFail($id);
        $task->restore();

        return redirect()->route('tasks.trash')
            ->with('success', 'Task restored successfully!');
    }

    /**
     * Permanently delete a task.
     */
    public function forceDelete($id)
    {
        $task = Task::withTrashed()->findOrFail($id);
        $task->forceDelete();

        return redirect()->route('tasks.trash')
            ->with('success', 'Task permanently deleted!');
    }
}

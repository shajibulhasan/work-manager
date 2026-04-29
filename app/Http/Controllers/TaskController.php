<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::where('user_id', Auth::id())->with('category');
        
        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        // Filter by priority
        if ($request->has('priority') && $request->priority != '') {
            $query->where('priority', $request->priority);
        }
        
        // Filter by category
        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }
        
        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        $tasks = $query->orderBy('due_date', 'asc')->paginate(10);
        
        $categories = Category::where('user_id', Auth::id())
                             ->where('type', 'task')
                             ->where('is_active', true)
                             ->get();
        
        $taskStats = [
            'total' => Task::where('user_id', Auth::id())->count(),
            'pending' => Task::where('user_id', Auth::id())->where('status', 'pending')->count(),
            'in_progress' => Task::where('user_id', Auth::id())->where('status', 'in_progress')->count(),
            'completed' => Task::where('user_id', Auth::id())->where('status', 'completed')->count(),
            'overdue' => Task::where('user_id', Auth::id())
                            ->where('due_date', '<', now())
                            ->where('status', '!=', 'completed')
                            ->count(),
        ];
        
        return view('tasks.index', compact('tasks', 'categories', 'taskStats'));
    }

    public function create()
    {
        $categories = Category::where('user_id', Auth::id())
                             ->where('type', 'task')
                             ->where('is_active', true)
                             ->get();
        return view('tasks.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'due_date' => 'nullable|date',
            'estimated_cost' => 'nullable|numeric|min:0',
            'actual_cost' => 'nullable|numeric|min:0',
            'assigned_to' => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'notes' => 'nullable|string|max:1000'
        ]);

        $validated['user_id'] = Auth::id();
        
        if ($validated['status'] === 'completed') {
            $validated['completed_at'] = now();
        }

        Task::create($validated);

        return redirect()->route('tasks.index')
                        ->with('success', 'Task created successfully!');
    }

    public function show(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403);
        }
        
        $task->load('category');
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403);
        }

        $categories = Category::where('user_id', Auth::id())
                             ->where('type', 'task')
                             ->where('is_active', true)
                             ->get();
                             
        return view('tasks.edit', compact('task', 'categories'));
    }

    public function update(Request $request, Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'due_date' => 'nullable|date',
            'estimated_cost' => 'nullable|numeric|min:0',
            'actual_cost' => 'nullable|numeric|min:0',
            'assigned_to' => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'notes' => 'nullable|string|max:1000'
        ]);

        if ($validated['status'] === 'completed' && !$task->completed_at) {
            $validated['completed_at'] = now();
        } elseif ($validated['status'] !== 'completed') {
            $validated['completed_at'] = null;
        }

        $task->update($validated);

        return redirect()->route('tasks.index')
                        ->with('success', 'Task updated successfully!');
    }

    public function destroy(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403);
        }

        $task->delete();

        return redirect()->route('tasks.index')
                        ->with('success', 'Task deleted successfully!');
    }

    // Quick status update via AJAX
    public function updateStatus(Request $request, Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled'
        ]);

        $updateData = ['status' => $request->status];
        
        if ($request->status === 'completed') {
            $updateData['completed_at'] = now();
        } else {
            $updateData['completed_at'] = null;
        }

        $task->update($updateData);

        // Check if this is an AJAX request
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'task' => $task]);
        }

        // For normal form submission, redirect back
        return back()->with('success', 'Task status updated to ' . ucfirst(str_replace('_', ' ', $request->status)) . '!');
    }
}
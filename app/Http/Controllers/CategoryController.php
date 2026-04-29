<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::where('user_id', Auth::id())
                             ->orderBy('type')
                             ->orderBy('name')
                             ->get();
        
        // Separate categories by type
        $expenseCategories = $categories->where('type', 'expense');
        $taskCategories = $categories->where('type', 'task');
        
        return view('categories.index', compact('categories', 'expenseCategories', 'taskCategories'));
    }

    public function create()
    {
         $icons = [
            'fas fa-utensils' => 'Food',
            'fas fa-car' => 'Transport',
            'fas fa-heartbeat' => 'Medical',
            'fas fa-film' => 'Entertainment',
            'fas fa-file-invoice-dollar' => 'Bills',
            'fas fa-graduation-cap' => 'Education',
            'fas fa-home' => 'Home',
            'fas fa-briefcase' => 'Work',
            'fas fa-gift' => 'Gift',
            'fas fa-plane' => 'Travel',
            'fas fa-tshirt' => 'Clothing',
            'fas fa-book' => 'Books',
            'fas fa-tools' => 'Tools',
            'fas fa-ellipsis-h' => 'Other'
        ];

        $colors = [
            '#e3342f' => 'Red',
            '#38c172' => 'Green',
            '#f6993f' => 'Orange',
            '#6610f2' => 'Deep Purple',
            '#28a745' => 'Dark Green',
            '#dc3545' => 'Dark Red',
        ];

        return view('categories.create', compact('icons', 'colors'));
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:expense,task',
            'color' => 'required|string|max:7',
            'icon' => 'required|string|max:50',
            'description' => 'nullable|string|max:500',
        ]);

        // Check for duplicate name within same type for this user
        $exists = Category::where('user_id', Auth::id())
                         ->where('name', $request->name)
                         ->where('type', $request->type)
                         ->exists();
        
        if ($exists) {
            return back()->withErrors(['name' => 'A category with this name already exists for this type.'])
                        ->withInput();
        }

        $validated['user_id'] = Auth::id();
        $validated['is_active'] = $request->has('is_active') ? true : false;

        Category::create($validated);

        return redirect()->route('categories.index')
                        ->with('success', 'Category "' . $request->name . '" created successfully!');
    }

    public function show(Category $category)
    {
        // Check ownership
        if ($category->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }
        
        $expenses = $category->expenses()->latest()->limit(10)->get();
        $tasks = $category->tasks()->latest()->limit(10)->get();
        $totalExpenses = $category->expenses()->sum('amount');
        $totalTasks = $category->tasks()->count();
        $completedTasks = $category->tasks()->where('status', 'completed')->count();
        
        return view('categories.show', compact(
            'category', 
            'expenses', 
            'tasks', 
            'totalExpenses', 
            'totalTasks', 
            'completedTasks'
        ));
    }

    public function edit(Category $category)
    {
        // Check ownership
        if ($category->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        $icons = [
            'fas fa-utensils' => 'Food',
            'fas fa-car' => 'Transport',
            'fas fa-heartbeat' => 'Medical',
            'fas fa-film' => 'Entertainment',
            'fas fa-file-invoice-dollar' => 'Bills',
            'fas fa-graduation-cap' => 'Education',
            'fas fa-home' => 'Home',
            'fas fa-briefcase' => 'Work',
            'fas fa-gift' => 'Gift',
            'fas fa-plane' => 'Travel',
            'fas fa-tshirt' => 'Clothing',
            'fas fa-book' => 'Books',
            'fas fa-tools' => 'Tools',
            'fas fa-ellipsis-h' => 'Other'
        ];

        $colors = [
            '#e3342f' => 'Red',
            '#38c172' => 'Green',
            '#f6993f' => 'Orange',
            '#6610f2' => 'Deep Purple',
            '#28a745' => 'Dark Green',
            '#dc3545' => 'Dark Red',
        ];

        return view('categories.edit', compact('category', 'icons', 'colors'));
    }

    public function update(Request $request, Category $category)
    {
        // Check ownership
        if ($category->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:expense,task',
            'color' => 'required|string|max:7',
            'icon' => 'required|string|max:50',
            'description' => 'nullable|string|max:500',
        ]);

        // Check for duplicate name (excluding current category)
        $exists = Category::where('user_id', Auth::id())
                         ->where('name', $request->name)
                         ->where('type', $request->type)
                         ->where('id', '!=', $category->id)
                         ->exists();
        
        if ($exists) {
            return back()->withErrors(['name' => 'A category with this name already exists for this type.'])
                        ->withInput();
        }

        $validated['is_active'] = $request->has('is_active') ? true : false;
        
        $category->update($validated);

        return redirect()->route('categories.index')
                        ->with('success', 'Category "' . $category->name . '" updated successfully!');
    }

    public function destroy(Category $category)
    {
        // Check ownership
        if ($category->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        // Check if category is being used
        $expenseCount = $category->expenses()->count();
        $taskCount = $category->tasks()->count();
        
        if ($expenseCount > 0 || $taskCount > 0) {
            return back()->with('error', 
                'Cannot delete category "' . $category->name . '". It is being used by ' . 
                $expenseCount . ' expense(s) and ' . $taskCount . ' task(s).'
            );
        }

        $categoryName = $category->name;
        $category->delete();

        return redirect()->route('categories.index')
                        ->with('success', 'Category "' . $categoryName . '" deleted successfully!');
    }

    // API method to get categories by type (for AJAX)
    public function getByType($type)
    {
        $categories = Category::where('user_id', Auth::id())
                             ->where('type', $type)
                             ->where('is_active', true)
                             ->orderBy('name')
                             ->get();
        return response()->json($categories);
    }
    
    // Toggle category status via AJAX
    public function toggleStatus(Category $category)
    {
        if ($category->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $category->update(['is_active' => !$category->is_active]);
        
        return response()->json([
            'success' => true,
            'is_active' => $category->is_active,
            'message' => 'Status updated successfully'
        ]);
    }
}
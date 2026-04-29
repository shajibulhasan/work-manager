<?php
// app/Http/Controllers/ExpenseController.php
namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = Expense::where('user_id', Auth::id());
        
        // Filter by category
        if ($request->has('category') && $request->category != '') {
            $query->where('expense_category', $request->category);
        }
        
        // Filter by date range
        if ($request->has('from_date') && $request->from_date != '') {
            $query->where('expense_date', '>=', $request->from_date);
        }
        if ($request->has('to_date') && $request->to_date != '') {
            $query->where('expense_date', '<=', $request->to_date);
        }
        
        $expenses = $query->orderBy('expense_date', 'desc')->paginate(10);
        
        // Get unique categories for filter dropdown
        $categories = Expense::where('user_id', Auth::id())
                            ->select('expense_category')
                            ->distinct()
                            ->pluck('expense_category');
        
        return view('expenses.index', compact('expenses', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('type', 'expense')
                                ->orderBy('created_at', 'desc')
                                ->get();
        return view('expenses.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'expense_category' => 'required|string|max:255',
            'spent_by' => 'required|string|max:255',
            'spent_at' => 'nullable|string|max:255',
            'paid_to' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'expense_date' => 'required|date'
        ]);

        $validated['user_id'] = Auth::id();
        Expense::create($validated);

        return redirect()->route('expenses.index')
                        ->with('success', 'Expense recorded successfully!');
    }

    public function show(Expense $expense)
    {
        if ($expense->user_id !== Auth::id()) {
            abort(403);
        }
        return view('expenses.show', compact('expense'));
    }

    public function edit(Expense $expense)
    {
        if ($expense->user_id !== Auth::id()) {
            abort(403);
        }
        $categories = Category::where('type', 'expense')
                                ->orderBy('created_at', 'desc')
                                ->get();
        return view('expenses.edit', compact('expense', 'categories'));
    }

    public function update(Request $request, Expense $expense)
    {
        if ($expense->user_id !== Auth::id()) {
            abort(403);
        }
        
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'expense_category' => 'required|string|max:255',
            'spent_by' => 'required|string|max:255',
            'spent_at' => 'nullable|string|max:255',
            'paid_to' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'expense_date' => 'required|date'
        ]);

        $expense->update($validated);

        return redirect()->route('expenses.index')
                        ->with('success', 'Expense updated successfully!');
    }

    public function destroy(Expense $expense)
    {
        if ($expense->user_id !== Auth::id()) {
            abort(403);
        }
        
        $expense->delete();

        return redirect()->route('expenses.index')
                        ->with('success', 'Expense deleted successfully!');
    }
}
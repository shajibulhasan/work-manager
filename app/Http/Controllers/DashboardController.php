<?php
// app/Http/Controllers/DashboardController.php
namespace App\Http\Controllers;

use App\Models\Deposit;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        
        $totalDeposits = Deposit::where('user_id', $userId)->sum('amount');
        $totalExpenses = Expense::where('user_id', $userId)->sum('amount');
        $currentBalance = $totalDeposits - $totalExpenses;
        
        $recentDeposits = Deposit::where('user_id', $userId)
                                ->orderBy('deposit_date', 'desc')
                                ->limit(5)
                                ->get();
                                
        $recentExpenses = Expense::where('user_id', $userId)
                                 ->orderBy('expense_date', 'desc')
                                 ->limit(5)
                                 ->get();
        
        // Get expense by category for chart
        $expensesByCategory = Expense::where('user_id', $userId)
                                    ->selectRaw('expense_category, SUM(amount) as total')
                                    ->groupBy('expense_category')
                                    ->get();
        
        return view('dashboard', compact(
            'totalDeposits',
            'totalExpenses',
            'currentBalance',
            'recentDeposits',
            'recentExpenses',
            'expensesByCategory'
        ));
    }
}
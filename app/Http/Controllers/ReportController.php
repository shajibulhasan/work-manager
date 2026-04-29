<?php

namespace App\Http\Controllers;

use App\Models\Deposit;
use App\Models\Expense;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function personWiseReport()
    {
        $userId = Auth::id();
        
        // Get all unique depositors
        $depositors = Deposit::where('user_id', $userId)
                            ->select('depositor_name')
                            ->distinct()
                            ->pluck('depositor_name');
        
        // Get all unique spenders
        $spenders = Expense::where('user_id', $userId)
                        ->select('spent_by')
                        ->distinct()
                        ->pluck('spent_by');
        
        // Get all unique receivers (received_by from deposits)
        $receivers = Deposit::where('user_id', $userId)
                            ->select('received_by')
                            ->distinct()
                            ->pluck('received_by');
        
        // Combine all unique persons
        $allPersons = $depositors->merge($spenders)->merge($receivers)->unique()->sort();
        
        // Get total expenses for calculation
        $totalExpenses = Expense::where('user_id', $userId)->sum('amount');
        $minimumDeposit = $totalExpenses / 3;
        
        $personReports = [];
        
        foreach ($allPersons as $person) {
            // Total deposits BY this person (money they gave)
            $totalDeposits = Deposit::where('user_id', $userId)
                                ->where('depositor_name', $person)
                                ->sum('amount');
            
            // Total expenses BY this person (money they spent)
            $totalSpent = Expense::where('user_id', $userId)
                            ->where('spent_by', $person)
                            ->sum('amount');
            
            // Total money RECEIVED BY this person (someone gave money to them)
            $totalReceived = Deposit::where('user_id', $userId)
                                ->where('received_by', $person)
                                ->sum('amount');
            
            // Count deposits
            $depositCount = Deposit::where('user_id', $userId)
                                ->where('depositor_name', $person)
                                ->count();
            
            // Count expenses
            $expenseCount = Expense::where('user_id', $userId)
                                ->where('spent_by', $person)
                                ->count();
            
            // Calculate due or excess based on deposits vs minimum required
            $difference = $totalDeposits - $minimumDeposit;
            
            if ($difference < -1) {
                $status = 'due';
            } elseif ($difference > 1) {
                $status = 'excess';
            } else {
                $status = 'balanced';
            }
            
            // Cash in hand = Money received (as receiver) - Money spent
            // This shows how much money they currently have from the system
            $cashInHand = $totalReceived - $totalSpent;
            
            $personReports[] = [
                'name' => $person,
                'total_deposits' => $totalDeposits,      // Money they deposited
                'total_spent' => $totalSpent,             // Money they spent
                'total_received' => $totalReceived,       // Money they received
                'deposit_count' => $depositCount,
                'expense_count' => $expenseCount,
                'minimum_required' => $minimumDeposit,
                'difference' => $difference,
                'status' => $status,
                'cash_in_hand' => $cashInHand,            // Received - Spent
            ];
        }
        
        return view('reports.person-wise', compact('personReports', 'totalExpenses', 'minimumDeposit'));
    }
    public function depositReport(Request $request)
    {
        $userId = Auth::id();
        
        $query = Deposit::where('user_id', $userId);
        
        // Filter by date range
        if ($request->has('from_date') && $request->from_date) {
            $query->where('deposit_date', '>=', $request->from_date);
        }
        if ($request->has('to_date') && $request->to_date) {
            $query->where('deposit_date', '<=', $request->to_date);
        }
        
        // Filter by depositor
        if ($request->has('depositor') && $request->depositor) {
            $query->where('depositor_name', 'like', '%' . $request->depositor . '%');
        }
        
        $deposits = $query->orderBy('deposit_date', 'desc')->paginate(20);
        
        // Summary statistics
        $totalDeposits = $query->sum('amount');
        $totalCount = $query->count();
        $averageDeposit = $totalCount > 0 ? $totalDeposits / $totalCount : 0;
        
        // Deposits by person
        $depositsByPerson = Deposit::where('user_id', $userId)
                                 ->select('depositor_name', DB::raw('SUM(amount) as total'), DB::raw('COUNT(*) as count'))
                                 ->groupBy('depositor_name')
                                 ->orderBy('total', 'desc')
                                 ->get();
        
        return view('reports.deposits', compact('deposits', 'totalDeposits', 'totalCount', 'averageDeposit', 'depositsByPerson'));
    }

    public function expenseReport(Request $request)
    {
        $userId = Auth::id();
        
        $query = Expense::where('user_id', $userId);
        
        // Filter by date range
        if ($request->has('from_date') && $request->from_date) {
            $query->where('expense_date', '>=', $request->from_date);
        }
        if ($request->has('to_date') && $request->to_date) {
            $query->where('expense_date', '<=', $request->to_date);
        }
        
        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->where('expense_category', $request->category);
        }
        
        // Filter by spender
        if ($request->has('spender') && $request->spender) {
            $query->where('spent_by', 'like', '%' . $request->spender . '%');
        }
        
        $expenses = $query->orderBy('expense_date', 'desc')->paginate(20);
        
        // Summary statistics
        $totalExpenses = $query->sum('amount');
        $totalCount = $query->count();
        $averageExpense = $totalCount > 0 ? $totalExpenses / $totalCount : 0;
        
        // Expenses by category
        $expensesByCategory = Expense::where('user_id', $userId)
                                   ->select('expense_category', DB::raw('SUM(amount) as total'), DB::raw('COUNT(*) as count'))
                                   ->groupBy('expense_category')
                                   ->orderBy('total', 'desc')
                                   ->get();
        
        // Expenses by person
        $expensesByPerson = Expense::where('user_id', $userId)
                                 ->select('spent_by', DB::raw('SUM(amount) as total'), DB::raw('COUNT(*) as count'))
                                 ->groupBy('spent_by')
                                 ->orderBy('total', 'desc')
                                 ->get();
        
        // Expenses by location
        $expensesByLocation = Expense::where('user_id', $userId)
                                   ->select('spent_at', DB::raw('SUM(amount) as total'), DB::raw('COUNT(*) as count'))
                                   ->groupBy('spent_at')
                                   ->orderBy('total', 'desc')
                                   ->get();
        
        $categories = Expense::where('user_id', $userId)
                           ->select('expense_category')
                           ->distinct()
                           ->pluck('expense_category');
        
        return view('reports.expenses', compact(
            'expenses', 'totalExpenses', 'totalCount', 'averageExpense',
            'expensesByCategory', 'expensesByPerson', 'expensesByLocation', 'categories'
        ));
    }

    public function dueReport()
    {
        $userId = Auth::id();
        
        $totalExpenses = Expense::where('user_id', $userId)->sum('amount');
        $minimumDeposit = $totalExpenses / 3;
        
        $depositors = Deposit::where('user_id', $userId)
                            ->select('depositor_name')
                            ->distinct()
                            ->pluck('depositor_name');
        
        $dueList = [];
        $excessList = [];
        $balancedList = [];
        
        foreach ($depositors as $person) {
            $totalDeposit = Deposit::where('user_id', $userId)
                                ->where('depositor_name', $person)
                                ->sum('amount');
            
            $difference = $totalDeposit - $minimumDeposit;
            
            $personData = [
                'name' => $person,
                'total_deposit' => $totalDeposit,
                'minimum_required' => $minimumDeposit,
                'difference' => $difference,
            ];
            
            // FIXED: Better threshold logic
            if ($difference < -1) {
                $personData['due_amount'] = abs($difference);
                $dueList[] = $personData;
            } elseif ($difference > 1) {
                $personData['excess_amount'] = $difference;
                $excessList[] = $personData;
            } else {
                $balancedList[] = $personData;
            }
        }
        
        return view('reports.due', compact('dueList', 'excessList', 'balancedList', 'totalExpenses', 'minimumDeposit'));
    }

    public function cashInHandReport()
    {
        $userId = Auth::id();
        
        $spenders = Expense::where('user_id', $userId)
                        ->select('spent_by')
                        ->distinct()
                        ->pluck('spent_by');
        
        $depositors = Deposit::where('user_id', $userId)
                            ->select('depositor_name')
                            ->distinct()
                            ->pluck('depositor_name');
        
        $receivers = Deposit::where('user_id', $userId)
                            ->select('received_by')
                            ->distinct()
                            ->pluck('received_by');
        
        $allPersons = $depositors->merge($spenders)->merge($receivers)->unique()->sort();
        
        $cashInHandList = collect([]); // Use collection
        $totalCashInHand = 0;
        
        foreach ($allPersons as $person) {
            $totalDeposits = Deposit::where('user_id', $userId)
                                ->where('depositor_name', $person)
                                ->sum('amount');
            
            $totalSpent = Expense::where('user_id', $userId)
                            ->where('spent_by', $person)
                            ->sum('amount');
            
            $totalReceived = Deposit::where('user_id', $userId)
                                ->where('received_by', $person)
                                ->sum('amount');
            
            $cashInHand = $totalReceived - $totalSpent;
            $totalCashInHand += $cashInHand;
            
            if ($cashInHand > 0) {
                $status = 'has_money';
                $statusText = 'Has Money';
                $statusClass = 'success';
            } elseif ($cashInHand < 0) {
                $status = 'overspent';
                $statusText = 'Overspent';
                $statusClass = 'danger';
            } else {
                $status = 'neutral';
                $statusText = 'Balanced';
                $statusClass = 'secondary';
            }
            
            $cashInHandList->push([
                'name' => $person,
                'total_deposits' => $totalDeposits,
                'total_spent' => $totalSpent,
                'total_received' => $totalReceived,
                'cash_in_hand' => $cashInHand,
                'status' => $status,
                'status_text' => $statusText,
                'status_class' => $statusClass,
            ]);
        }
        
        return view('reports.cash-in-hand', compact('cashInHandList', 'totalCashInHand'));
    }

    public function receivedByReport()
    {
        $userId = Auth::id();
        
        // Deposits received by each person
        $receivedByList = Deposit::where('user_id', $userId)
                               ->select(
                                   'received_by',
                                   DB::raw('SUM(amount) as total_amount'),
                                   DB::raw('COUNT(*) as total_count'),
                                   DB::raw('AVG(amount) as average_amount'),
                                   DB::raw('MAX(amount) as max_amount'),
                                   DB::raw('MIN(amount) as min_amount')
                               )
                               ->groupBy('received_by')
                               ->orderBy('total_amount', 'desc')
                               ->get();
        
        return view('reports.received-by', compact('receivedByList'));
    }

    public function monthlyReport(Request $request)
    {
        $userId = Auth::id();
        $year = $request->year ?? date('Y');
        
        // Monthly deposits
        $monthlyDeposits = Deposit::where('user_id', $userId)
                                ->whereYear('deposit_date', $year)
                                ->select(
                                    DB::raw('MONTH(deposit_date) as month'),
                                    DB::raw('SUM(amount) as total')
                                )
                                ->groupBy('month')
                                ->pluck('total', 'month');
        
        // Monthly expenses
        $monthlyExpenses = Expense::where('user_id', $userId)
                                ->whereYear('expense_date', $year)
                                ->select(
                                    DB::raw('MONTH(expense_date) as month'),
                                    DB::raw('SUM(amount) as total')
                                )
                                ->groupBy('month')
                                ->pluck('total', 'month');
        
        $monthlyData = [];
        $months = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
        ];
        
        foreach ($months as $num => $name) {
            $deposit = $monthlyDeposits[$num] ?? 0;
            $expense = $monthlyExpenses[$num] ?? 0;
            
            $monthlyData[] = [
                'month_num' => $num,
                'month_name' => $name,
                'total_deposits' => $deposit,
                'total_expenses' => $expense,
                'balance' => $deposit - $expense,
            ];
        }
        
        $years = range(date('Y') - 2, date('Y') + 1);
        
        return view('reports.monthly', compact('monthlyData', 'year', 'years'));
    }
}
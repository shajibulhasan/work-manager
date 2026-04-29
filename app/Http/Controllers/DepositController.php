<?php
// app/Http/Controllers/DepositController.php
namespace App\Http\Controllers;

use App\Models\Deposit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepositController extends Controller
{
    public function index()
    {
        $deposits = Deposit::where('user_id', Auth::id())
                          ->orderBy('deposit_date', 'desc')
                          ->paginate(10);
        return view('deposits.index', compact('deposits'));
    }

    public function create()
    {
        return view('deposits.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'depositor_name' => 'required|string|max:255',
            'received_by' => 'required|string|max:255',
            'deposit_date' => 'required|date',
            'purpose' => 'nullable|string|max:500'
        ]);

        $validated['user_id'] = Auth::id();
        Deposit::create($validated);

        return redirect()->route('deposits.index')
                        ->with('success', 'Deposit recorded successfully!');
    }

    public function show(Deposit $deposit)
    {
        if ($deposit->user_id !== Auth::id()) {
            abort(403);
        }
        return view('deposits.show', compact('deposit'));
    }

    public function edit(Deposit $deposit)
    {
        if ($deposit->user_id !== Auth::id()) {
            abort(403);
        }
        return view('deposits.edit', compact('deposit'));
    }

    public function update(Request $request, Deposit $deposit)
    {
        if ($deposit->user_id !== Auth::id()) {
            abort(403);
        }
        
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'depositor_name' => 'required|string|max:255',
            'received_by' => 'required|string|max:255',
            'deposit_date' => 'required|date',
            'purpose' => 'nullable|string|max:500'
        ]);

        $deposit->update($validated);

        return redirect()->route('deposits.index')
                        ->with('success', 'Deposit updated successfully!');
    }

    public function destroy(Deposit $deposit)
    {
        if ($deposit->user_id !== Auth::id()) {
            abort(403);
        }
        
        $deposit->delete();

        return redirect()->route('deposits.index')
                        ->with('success', 'Deposit deleted successfully!');
    }
}
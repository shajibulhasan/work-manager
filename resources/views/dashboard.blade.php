@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card text-white bg-success mb-3">
            <div class="card-body">
                <h5 class="card-title">Total Deposits</h5>
                <h2 class="card-text">${{ number_format($totalDeposits, 2) }}</h2>
                <small>Total amount deposited</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-danger mb-3">
            <div class="card-body">
                <h5 class="card-title">Total Expenses</h5>
                <h2 class="card-text">${{ number_format($totalExpenses, 2) }}</h2>
                <small>Total amount spent</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white {{ $currentBalance >= 0 ? 'bg-info' : 'bg-warning' }} mb-3">
            <div class="card-body">
                <h5 class="card-title">Current Balance</h5>
                <h2 class="card-text">${{ number_format($currentBalance, 2) }}</h2>
                <small>Available balance in hand</small>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5><i class="fas fa-arrow-down"></i> Recent Deposits</h5>
            </div>
            <div class="card-body">
                @if($recentDeposits->count() > 0)
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Depositor</th>
                            <th>Received By</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentDeposits as $deposit)
                        <tr>
                            <td>{{ $deposit->deposit_date->format('d M Y') }}</td>
                            <td>{{ $deposit->depositor_name }}</td>
                            <td>{{ $deposit->received_by }}</td>
                            <td class="text-success">${{ number_format($deposit->amount, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <p class="text-muted">No deposits yet.</p>
                @endif
                <a href="{{ route('deposits.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-danger text-white">
                <h5><i class="fas fa-arrow-up"></i> Recent Expenses</h5>
            </div>
            <div class="card-body">
                @if($recentExpenses->count() > 0)
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Spent By</th>
                            <th>Spent At</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentExpenses as $expense)
                        <tr>
                            <td>{{ $expense->expense_date->format('d M Y') }}</td>
                            <td>{{ $expense->spent_by }}</td>
                            <td>{{ $expense->spent_at }}</td>
                            <td class="text-danger">${{ number_format($expense->amount, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <p class="text-muted">No expenses yet.</p>
                @endif
                <a href="{{ route('expenses.index') }}" class="btn btn-sm btn-outline-danger">View All</a>
            </div>
        </div>
    </div>
</div>

@if($expensesByCategory->count() > 0)
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5><i class="fas fa-chart-pie"></i> Expenses by Category</h5>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Total Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($expensesByCategory as $category)
                        <tr>
                            <td>{{ $category->expense_category }}</td>
                            <td>${{ number_format($category->total, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-arrow-up"></i> Expense Report</h2>
    <a href="{{ route('reports.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Reports
    </a>
</div>

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-danger text-white">
            <div class="card-body text-center">
                <h5>Total Expenses</h5>
                <h3>${{ number_format($totalExpenses, 2) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <h5>Total Count</h5>
                <h3>{{ $totalCount }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <h5>Average Expense</h5>
                <h3>${{ number_format($averageExpense, 2) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <h5>Categories</h5>
                <h3>{{ $categories->count() }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-header bg-danger text-white">
        <h5><i class="fas fa-filter"></i> Filter Expenses</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('reports.expenses') }}" method="GET" class="row g-3">
            <div class="col-md-3">
                <label for="from_date" class="form-label">From Date</label>
                <input type="date" class="form-control" id="from_date" name="from_date" 
                       value="{{ request('from_date') }}">
            </div>
            <div class="col-md-3">
                <label for="to_date" class="form-label">To Date</label>
                <input type="date" class="form-control" id="to_date" name="to_date" 
                       value="{{ request('to_date') }}">
            </div>
            <div class="col-md-3">
                <label for="category" class="form-label">Category</label>
                <select class="form-select" id="category" name="category">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                            {{ $cat }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="spender" class="form-label">Spender Name</label>
                <input type="text" class="form-control" id="spender" name="spender" 
                       placeholder="Search spender..." value="{{ request('spender') }}">
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-search"></i> Apply Filters
                </button>
                <a href="{{ route('reports.expenses') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Clear
                </a>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <!-- Expenses by Category -->
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header bg-danger text-white">
                <h5><i class="fas fa-tags"></i> By Category</h5>
            </div>
            <div class="card-body">
                @if($expensesByCategory->count() > 0)
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Amount</th>
                            <th>Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($expensesByCategory as $cat)
                        <tr>
                            <td>{{ $cat->expense_category }}</td>
                            <td class="text-danger">${{ number_format($cat->total, 2) }}</td>
                            <td>{{ $cat->count }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <p class="text-muted text-center">No data</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Expenses by Person -->
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header bg-warning text-white">
                <h5><i class="fas fa-user"></i> By Person</h5>
            </div>
            <div class="card-body">
                @if($expensesByPerson->count() > 0)
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Person</th>
                            <th>Amount</th>
                            <th>Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($expensesByPerson as $person)
                        <tr>
                            <td>{{ $person->spent_by }}</td>
                            <td class="text-danger">${{ number_format($person->total, 2) }}</td>
                            <td>{{ $person->count }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <p class="text-muted text-center">No data</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Expenses by Location -->
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5><i class="fas fa-map-marker-alt"></i> By Location</h5>
            </div>
            <div class="card-body">
                @if($expensesByLocation->count() > 0)
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Location</th>
                            <th>Amount</th>
                            <th>Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($expensesByLocation as $location)
                        <tr>
                            <td>{{ $location->spent_at }}</td>
                            <td class="text-danger">${{ number_format($location->total, 2) }}</td>
                            <td>{{ $location->count }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <p class="text-muted text-center">No data</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- All Expenses List -->
<div class="card">
    <div class="card-header bg-dark text-white">
        <h5><i class="fas fa-list"></i> All Expenses</h5>
    </div>
    <div class="card-body">
        @if($expenses->count() > 0)
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Date</th>
                        <th>Category</th>
                        <th>Spent By</th>
                        <th>Spent At</th>
                        <th>Paid To</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($expenses as $expense)
                    <tr>
                        <td>{{ $expense->expense_date->format('d M Y') }}</td>
                        <td><span class="badge bg-info">{{ $expense->expense_category }}</span></td>
                        <td>{{ $expense->spent_by }}</td>
                        <td>{{ $expense->spent_at }}</td>
                        <td>{{ $expense->paid_to ?? 'N/A' }}</td>
                        <td class="text-danger">${{ number_format($expense->amount, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $expenses->appends(request()->query())->links() }}
        @else
        <p class="text-muted text-center">No expenses found</p>
        @endif
    </div>
</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-arrow-up"></i> All Expenses</h2>
    <a href="{{ route('expenses.create') }}" class="btn btn-danger">
        <i class="fas fa-plus"></i> Add New Expense
    </a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('expenses.index') }}" method="GET" class="row g-3">
            <div class="col-md-3">
                <label for="category" class="form-label">Category</label>
                <select class="form-select" id="category" name="category">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}" 
                                {{ request('category') == $category ? 'selected' : '' }}>
                            {{ $category }}
                        </option>
                    @endforeach
                </select>
            </div>
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
                <label class="form-label">&nbsp;</label>
                <div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <a href="{{ route('expenses.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Clear
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        @if($expenses->count() > 0)
        <div class="table-responsive">
            <table class="table table-striped">
                <thead class="table-success">
                    <tr>
                        <th>Date</th>
                        <th>Category</th>
                        <th>Spent By</th>
                        <th>Spent At</th>
                        <th>Paid To</th>
                        <th>Amount</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($expenses as $expense)
                    <tr>
                        <td>{{ $expense->expense_date->format('d M Y') }}</td>
                        <td>
                            <span class="badge bg-success">{{ $expense->expense_category }}</span>
                        </td>
                        <td>{{ $expense->spent_by }}</td>
                        <td>{{ $expense->spent_at }}</td>
                        <td>{{ $expense->paid_to ?? 'N/A' }}</td>
                        <td class="text-danger">${{ number_format($expense->amount, 2) }}</td>
                        <td>
                            <a href="{{ route('expenses.show', $expense) }}" 
                               class="btn btn-info btn-sm" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('expenses.edit', $expense) }}" 
                               class="btn btn-warning btn-sm" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('expenses.destroy', $expense) }}" 
                                  method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" 
                                        onclick="return confirm('Are you sure you want to delete this expense?')"
                                        title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $expenses->links() }}
        @else
        <div class="text-center py-4">
            <i class="fas fa-inbox fa-3x text-muted"></i>
            <p class="mt-2">No expenses found.</p>
            <a href="{{ route('expenses.create') }}" class="btn btn-danger">Add Expense</a>
        </div>
        @endif
    </div>
</div>
@endsection
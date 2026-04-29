@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5><i class="fas fa-eye"></i> Expense Details</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">Amount</th>
                        <td class="text-danger">${{ number_format($expense->amount, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Category</th>
                        <td><span class="badge bg-success">{{ $expense->expense_category }}</span></td>
                    </tr>
                    <tr>
                        <th>Spent By</th>
                        <td>{{ $expense->spent_by }}</td>
                    </tr>
                    <tr>
                        <th>Spent At</th>
                        <td>{{ $expense->spent_at }}</td>
                    </tr>
                    <tr>
                        <th>Paid To</th>
                        <td>{{ $expense->paid_to ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Expense Date</th>
                        <td>{{ $expense->expense_date->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <td>{{ $expense->description ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>{{ $expense->created_at->format('d M Y, h:i A') }}</td>
                    </tr>
                    <tr>
                        <th>Last Updated</th>
                        <td>{{ $expense->updated_at->format('d M Y, h:i A') }}</td>
                    </tr>
                </table>
                
                <div class="mt-3">
                    <a href="{{ route('expenses.edit', $expense) }}" class="btn btn-danger">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="{{ route('expenses.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
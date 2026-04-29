@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-arrow-down"></i> All Deposits</h2>
    <a href="{{ route('deposits.create') }}" class="btn btn-success">
        <i class="fas fa-plus"></i> Add New Deposit
    </a>
</div>

<div class="card">
    <div class="card-body">
        @if($deposits->count() > 0)
        <div class="table-responsive">
            <table class="table table-striped">
                <thead class="table-success">
                    <tr>
                        <th>Date</th>
                        <th>Depositor Name</th>
                        <th>Received By</th>
                        <th>Amount</th>
                        <th>Purpose</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($deposits as $deposit)
                    <tr>
                        <td>{{ $deposit->deposit_date->format('d M Y') }}</td>
                        <td>{{ $deposit->depositor_name }}</td>
                        <td>{{ $deposit->received_by }}</td>
                        <td class="text-success">${{ number_format($deposit->amount, 2) }}</td>
                        <td>{{ Str::limit($deposit->purpose, 30) }}</td>
                        <td>
                            <a href="{{ route('deposits.show', $deposit) }}" 
                               class="btn btn-info btn-sm" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('deposits.edit', $deposit) }}" 
                               class="btn btn-warning btn-sm" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('deposits.destroy', $deposit) }}" 
                                  method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" 
                                        onclick="return confirm('Are you sure you want to delete this deposit?')"
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
        {{ $deposits->links() }}
        @else
        <div class="text-center py-4">
            <i class="fas fa-inbox fa-3x text-muted"></i>
            <p class="mt-2">No deposits found. Start by adding your first deposit!</p>
            <a href="{{ route('deposits.create') }}" class="btn btn-success">Add Deposit</a>
        </div>
        @endif
    </div>
</div>
@endsection
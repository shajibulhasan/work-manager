@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-arrow-down"></i> Deposit Report</h2>
    <a href="{{ route('reports.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Reports
    </a>
</div>

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h5>Total Deposits</h5>
                <h3>${{ number_format($totalDeposits, 2) }}</h3>
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
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <h5>Average Deposit</h5>
                <h3>${{ number_format($averageDeposit, 2) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <h5>Total Depositors</h5>
                <h3>{{ $depositsByPerson->count() }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h5><i class="fas fa-filter"></i> Filter Deposits</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('reports.deposits') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <label for="from_date" class="form-label">From Date</label>
                <input type="date" class="form-control" id="from_date" name="from_date" 
                       value="{{ request('from_date') }}">
            </div>
            <div class="col-md-4">
                <label for="to_date" class="form-label">To Date</label>
                <input type="date" class="form-control" id="to_date" name="to_date" 
                       value="{{ request('to_date') }}">
            </div>
            <div class="col-md-4">
                <label for="depositor" class="form-label">Depositor Name</label>
                <input type="text" class="form-control" id="depositor" name="depositor" 
                       placeholder="Search depositor..." value="{{ request('depositor') }}">
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Apply Filters
                </button>
                <a href="{{ route('reports.deposits') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Clear
                </a>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <!-- Deposits by Person -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5><i class="fas fa-users"></i> Deposits by Person</h5>
            </div>
            <div class="card-body">
                @if($depositsByPerson->count() > 0)
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Person</th>
                                <th>Amount</th>
                                <th>Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($depositsByPerson as $person)
                            <tr>
                                <td>{{ $person->depositor_name }}</td>
                                <td class="text-success">${{ number_format($person->total, 2) }}</td>
                                <td>{{ $person->count }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-muted text-center">No data available</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Deposits List -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5><i class="fas fa-list"></i> All Deposits</h5>
            </div>
            <div class="card-body">
                @if($deposits->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Date</th>
                                <th>Depositor</th>
                                <th>Received By</th>
                                <th>Amount</th>
                                <th>Purpose</th>
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
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $deposits->appends(request()->query())->links() }}
                @else
                <p class="text-muted text-center">No deposits found</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-users"></i> Person Wise Report</h2>
    <a href="{{ route('reports.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Reports
    </a>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h5>Total Expenses</h5>
                <h3>${{ number_format($totalExpenses, 2) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h5>Minimum Deposit Required</h5>
                <h3>${{ number_format($minimumDeposit, 2) }}</h3>
                <small>Total Expenses ÷ 3</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5>Total Persons</h5>
                <h3>{{ count($personReports) }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header bg-primary text-white">
        <h5><i class="fas fa-table"></i> Person Wise Summary</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Person Name</th>
                        <th>Total Deposits</th>
                        <th>Total Received</th>
                        <th>Total Spent</th>
                        <th>Min. Required</th>
                        <th>Difference</th>
                        <th>Status</th>
                        <th>Cash in Hand</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($personReports as $report)
                    <tr>
                        <td><strong>{{ $report['name'] }}</strong></td>
                        <td class="text-success">
                            ${{ number_format($report['total_deposits'], 2) }}
                            <br><small>({{ $report['deposit_count'] }} deposits)</small>
                        </td>
                        <td class="text-primary">
                            ${{ number_format($report['total_received'], 2) }}
                        </td>
                        <td class="text-danger">
                            ${{ number_format($report['total_spent'], 2) }}
                            <br><small>({{ $report['expense_count'] }} expenses)</small>
                        </td>
                        <td>${{ number_format($report['minimum_required'], 2) }}</td>
                        <td>
                            @if($report['difference'] > 0)
                                <span class="text-success">+${{ number_format($report['difference'], 2) }}</span>
                            @elseif($report['difference'] < 0)
                                <span class="text-danger">-${{ number_format(abs($report['difference']), 2) }}</span>
                            @else
                                <span class="text-muted">$0.00</span>
                            @endif
                        </td>
                        <td>
                            @if($report['status'] == 'due')
                                <span class="badge bg-danger">Due</span>
                            @elseif($report['status'] == 'excess')
                                <span class="badge bg-success">Excess</span>
                            @else
                                <span class="badge bg-info">Balanced</span>
                            @endif
                        </td>
                        <td>
                            @if($report['cash_in_hand'] >= 0)
                                <span class="text-success">${{ number_format($report['cash_in_hand'], 2) }}</span>
                            @else
                                <span class="text-danger">-${{ number_format(abs($report['cash_in_hand']), 2) }}</span>
                            @endif
                        </td>
                        <td>
                            <small>
                                Deposits: {{ $report['deposit_count'] }} | 
                                Expenses: {{ $report['expense_count'] }}
                            </small>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header bg-info text-white">
        <h5><i class="fas fa-info-circle"></i> Report Legend</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <h6><span class="badge bg-danger">Due</span></h6>
                <p class="text-muted">Person has deposited less than the minimum required amount (Total Expenses ÷ 3)</p>
            </div>
            <div class="col-md-4">
                <h6><span class="badge bg-success">Excess</span></h6>
                <p class="text-muted">Person has deposited more than the minimum required amount</p>
            </div>
            <div class="col-md-4">
                <h6><span class="badge bg-info">Balanced</span></h6>
                <p class="text-muted">Person has deposited approximately the minimum required amount</p>
            </div>
        </div>
    </div>
</div>
@endsection
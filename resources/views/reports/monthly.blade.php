@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-calendar-alt"></i> Monthly Report</h2>
    <a href="{{ route('reports.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Reports
    </a>
</div>

<!-- Year Selector -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('reports.monthly') }}" method="GET" class="row g-3 align-items-center">
            <div class="col-auto">
                <label for="year" class="form-label"><strong>Select Year:</strong></label>
            </div>
            <div class="col-auto">
                <select class="form-select" id="year" name="year" onchange="this.form.submit()">
                    @foreach($years as $y)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>
</div>

<!-- Monthly Data Table -->
<div class="card">
    <div class="card-header bg-primary text-white">
        <h5><i class="fas fa-table"></i> Monthly Report - {{ $year }}</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Month</th>
                        <th>Total Deposits</th>
                        <th>Total Expenses</th>
                        <th>Balance</th>
                        <th>Status</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    @php 
                        $yearTotalDeposits = 0;
                        $yearTotalExpenses = 0;
                    @endphp
                    @foreach($monthlyData as $data)
                        @php 
                            $yearTotalDeposits += $data['total_deposits'];
                            $yearTotalExpenses += $data['total_expenses'];
                        @endphp
                        <tr>
                            <td><strong>{{ $data['month_name'] }}</strong></td>
                            <td class="text-success">
                                @if($data['total_deposits'] > 0)
                                    ${{ number_format($data['total_deposits'], 2) }}
                                @else
                                    <span class="text-muted">$0.00</span>
                                @endif
                            </td>
                            <td class="text-danger">
                                @if($data['total_expenses'] > 0)
                                    ${{ number_format($data['total_expenses'], 2) }}
                                @else
                                    <span class="text-muted">$0.00</span>
                                @endif
                            </td>
                            <td>
                                @if($data['balance'] > 0)
                                    <span class="text-success">+${{ number_format($data['balance'], 2) }}</span>
                                @elseif($data['balance'] < 0)
                                    <span class="text-danger">-${{ number_format(abs($data['balance']), 2) }}</span>
                                @else
                                    <span class="text-muted">$0.00</span>
                                @endif
                            </td>
                            <td>
                                @if($data['balance'] > 0)
                                    <span class="badge bg-success">Profit</span>
                                @elseif($data['balance'] < 0)
                                    <span class="badge bg-danger">Loss</span>
                                @else
                                    <span class="badge bg-secondary">Even</span>
                                @endif
                            </td>
                            <td>
                                @if($data['total_deposits'] > 0 || $data['total_expenses'] > 0)
                                    <div class="progress" style="height: 15px;">
                                        @php
                                            $max = max($data['total_deposits'], $data['total_expenses']);
                                            $depositPercent = $max > 0 ? ($data['total_deposits'] / $max) * 100 : 0;
                                            $expensePercent = $max > 0 ? ($data['total_expenses'] / $max) * 100 : 0;
                                        @endphp
                                        <div class="progress-bar bg-success" style="width: {{ $depositPercent }}%">
                                            @if($depositPercent > 20) D @endif
                                        </div>
                                        <div class="progress-bar bg-danger" style="width: {{ $expensePercent }}%">
                                            @if($expensePercent > 20) E @endif
                                        </div>
                                    </div>
                                    <small class="text-muted">D=Deposits, E=Expenses</small>
                                @else
                                    <span class="text-muted">No transactions</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-dark">
                    <tr>
                        <th>Year Total</th>
                        <th class="text-success">${{ number_format($yearTotalDeposits, 2) }}</th>
                        <th class="text-danger">${{ number_format($yearTotalExpenses, 2) }}</th>
                        <th>
                            @php $yearBalance = $yearTotalDeposits - $yearTotalExpenses; @endphp
                            @if($yearBalance > 0)
                                <span class="text-success">+${{ number_format($yearBalance, 2) }}</span>
                            @elseif($yearBalance < 0)
                                <span class="text-danger">-${{ number_format(abs($yearBalance), 2) }}</span>
                            @else
                                <span class="text-muted">$0.00</span>
                            @endif
                        </th>
                        <th colspan="2"></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<!-- Year Summary Cards -->
<div class="row mt-4">
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h5>Year Total Deposits</h5>
                <h3>${{ number_format($yearTotalDeposits, 2) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-danger text-white">
            <div class="card-body text-center">
                <h5>Year Total Expenses</h5>
                <h3>${{ number_format($yearTotalExpenses, 2) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-{{ $yearBalance >= 0 ? 'info' : 'warning' }} text-white">
            <div class="card-body text-center">
                <h5>Year Net Balance</h5>
                <h3>${{ number_format($yearBalance, 2) }}</h3>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-exclamation-triangle"></i> Due Report</h2>
    <a href="{{ route('reports.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Reports
    </a>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h5>Total Expenses</h5>
                <h3>${{ number_format($totalExpenses, 2) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h5>Minimum Deposit Required Per Person</h5>
                <h3>${{ number_format($minimumDeposit, 2) }}</h3>
                <small>Total Expenses ÷ 3</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Due List -->
    <div class="col-md-4">
        <div class="card border-danger">
            <div class="card-header bg-danger text-white">
                <h5><i class="fas fa-times-circle"></i> Due List ({{ count($dueList) }})</h5>
            </div>
            <div class="card-body">
                @if(count($dueList) > 0)
                    @foreach($dueList as $due)
                    <div class="alert alert-danger">
                        <h6>{{ $due['name'] }}</h6>
                        <p class="mb-1">Deposited: <strong>${{ number_format($due['total_deposit'], 2) }}</strong></p>
                        <p class="mb-1">Required: <strong>${{ number_format($due['minimum_required'], 2) }}</strong></p>
                        <p class="mb-0">
                            Due Amount: 
                            <strong>${{ number_format($due['due_amount'], 2) }}</strong>
                        </p>
                    </div>
                    @endforeach
                @else
                    <div class="text-center text-muted py-3">
                        <i class="fas fa-check-circle fa-2x text-success"></i>
                        <p class="mt-2">No one is due!</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Balanced List -->
    <div class="col-md-4">
        <div class="card border-info">
            <div class="card-header bg-info text-white">
                <h5><i class="fas fa-check-circle"></i> Balanced ({{ count($balancedList) }})</h5>
            </div>
            <div class="card-body">
                @if(count($balancedList) > 0)
                    @foreach($balancedList as $balanced)
                    <div class="alert alert-info">
                        <h6>{{ $balanced['name'] }}</h6>
                        <p class="mb-1">Deposited: <strong>${{ number_format($balanced['total_deposit'], 2) }}</strong></p>
                        <p class="mb-0">Required: <strong>${{ number_format($balanced['minimum_required'], 2) }}</strong></p>
                    </div>
                    @endforeach
                @else
                    <div class="text-center text-muted py-3">
                        <i class="fas fa-info-circle fa-2x"></i>
                        <p class="mt-2">No one is exactly balanced</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Excess List -->
    <div class="col-md-4">
        <div class="card border-success">
            <div class="card-header bg-success text-white">
                <h5><i class="fas fa-plus-circle"></i> Excess List ({{ count($excessList) }})</h5>
            </div>
            <div class="card-body">
                @if(count($excessList) > 0)
                    @foreach($excessList as $excess)
                    <div class="alert alert-success">
                        <h6>{{ $excess['name'] }}</h6>
                        <p class="mb-1">Deposited: <strong>${{ number_format($excess['total_deposit'], 2) }}</strong></p>
                        <p class="mb-1">Required: <strong>${{ number_format($excess['minimum_required'], 2) }}</strong></p>
                        <p class="mb-0">
                            Excess: 
                            <strong>${{ number_format($excess['excess_amount'], 2) }}</strong>
                        </p>
                    </div>
                    @endforeach
                @else
                    <div class="text-center text-muted py-3">
                        <i class="fas fa-info-circle fa-2x"></i>
                        <p class="mt-2">No excess deposits</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
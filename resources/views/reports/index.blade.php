@extends('layouts.app')

@section('content')
<h2 class="mb-4"><i class="fas fa-chart-bar"></i> Reports Dashboard</h2>

<div class="row">
    <div class="col-md-4 mb-4">
        <a href="{{ route('reports.person-wise') }}" class="text-decoration-none">
            <div class="card bg-primary text-white h-100">
                <div class="card-body text-center">
                    <i class="fas fa-users fa-3x mb-3"></i>
                    <h5>Person Wise Report</h5>
                    <p>View per person deposits, expenses, dues and cash in hand</p>
                </div>
            </div>
        </a>
    </div>
    
    <div class="col-md-4 mb-4">
        <a href="{{ route('reports.deposits') }}" class="text-decoration-none">
            <div class="card bg-success text-white h-100">
                <div class="card-body text-center">
                    <i class="fas fa-arrow-down fa-3x mb-3"></i>
                    <h5>Deposit Report</h5>
                    <p>Detailed deposit analysis with filters</p>
                </div>
            </div>
        </a>
    </div>
    
    <div class="col-md-4 mb-4">
        <a href="{{ route('reports.expenses') }}" class="text-decoration-none">
            <div class="card bg-danger text-white h-100">
                <div class="card-body text-center">
                    <i class="fas fa-arrow-up fa-3x mb-3"></i>
                    <h5>Expense Report</h5>
                    <p>Expense analysis by category, person and location</p>
                </div>
            </div>
        </a>
    </div>
    
    <div class="col-md-4 mb-4">
        <a href="{{ route('reports.due') }}" class="text-decoration-none">
            <div class="card bg-warning text-white h-100">
                <div class="card-body text-center">
                    <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                    <h5>Due Report</h5>
                    <p>Who needs to deposit more money</p>
                </div>
            </div>
        </a>
    </div>
    
    <div class="col-md-4 mb-4">
        <a href="{{ route('reports.cash-in-hand') }}" class="text-decoration-none">
            <div class="card bg-info text-white h-100">
                <div class="card-body text-center">
                    <i class="fas fa-wallet fa-3x mb-3"></i>
                    <h5>Cash in Hand</h5>
                    <p>Current cash balance per person</p>
                </div>
            </div>
        </a>
    </div>
    
    <div class="col-md-4 mb-4">
        <a href="{{ route('reports.received-by') }}" class="text-decoration-none">
            <div class="card bg-secondary text-white h-100">
                <div class="card-body text-center">
                    <i class="fas fa-hand-holding-usd fa-3x mb-3"></i>
                    <h5>Received By Report</h5>
                    <p>Who received how much money</p>
                </div>
            </div>
        </a>
    </div>
    
    <div class="col-md-4 mb-4">
        <a href="{{ route('reports.monthly') }}" class="text-decoration-none">
            <div class="card bg-dark text-white h-100">
                <div class="card-body text-center">
                    <i class="fas fa-calendar-alt fa-3x mb-3"></i>
                    <h5>Monthly Report</h5>
                    <p>Month by month income and expense analysis</p>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection
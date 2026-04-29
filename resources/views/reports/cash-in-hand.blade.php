@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-wallet"></i> Cash in Hand Report</h2>
    <a href="{{ route('reports.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Reports
    </a>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card {{ $totalCashInHand >= 0 ? 'bg-success' : 'bg-danger' }} text-white">
            <div class="card-body text-center">
                <h5>Total Cash in Hand (All Persons)</h5>
                <h2>${{ number_format($totalCashInHand, 2) }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header bg-primary text-white">
        <h5><i class="fas fa-table"></i> Per Person Cash in Hand</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Person Name</th>
                        <th>Total Received</th>
                        <th>Total Spent</th>
                        <th>Cash in Hand</th>
                        <th>Status</th>
                        <th>Summary</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cashInHandList as $item)
                    <tr>
                        <td><strong>{{ $item['name'] }}</strong></td>
                        <td class="text-primary">
                            @if($item['total_received'] > 0)
                                ${{ number_format($item['total_received'], 2) }}
                            @else
                                <span class="text-muted">$0.00</span>
                            @endif
                        </td>
                        <td class="text-danger">
                            @if($item['total_spent'] > 0)
                                ${{ number_format($item['total_spent'], 2) }}
                            @else
                                <span class="text-muted">$0.00</span>
                            @endif
                        </td>
                        <td>
                            <strong>
                                @if($item['cash_in_hand'] > 0)
                                    <span class="text-success">+${{ number_format($item['cash_in_hand'], 2) }}</span>
                                @elseif($item['cash_in_hand'] < 0)
                                    <span class="text-danger">-${{ number_format(abs($item['cash_in_hand']), 2) }}</span>
                                @else
                                    <span class="text-muted">$0.00</span>
                                @endif
                            </strong>
                        </td>
                        <td>
                            @if($item['status'] == 'has_money')
                                <span class="badge bg-success">Has Money</span>
                            @elseif($item['status'] == 'overspent')
                                <span class="badge bg-danger">Overspent</span>
                            @else
                                <span class="badge bg-secondary">Balanced</span>
                            @endif
                        </td>
                        <td>
                            @if($item['cash_in_hand'] > 0)
                                <span class="text-success">
                                    <i class="fas fa-arrow-up"></i> 
                                    Has ${{ number_format($item['cash_in_hand'], 2) }} extra
                                </span>
                            @elseif($item['cash_in_hand'] < 0)
                                <span class="text-danger">
                                    <i class="fas fa-arrow-down"></i> 
                                    Overspent by ${{ number_format(abs($item['cash_in_hand']), 2) }}
                                </span>
                            @else
                                <span class="text-muted">
                                    <i class="fas fa-equals"></i> 
                                    Exactly balanced (Received = Spent)
                                </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Status Summary Cards -->
<div class="row mt-4">
    @php
        $hasMoneyCount = $cashInHandList->where('status', 'has_money')->count();
        $overspentCount = $cashInHandList->where('status', 'overspent')->count();
        $balancedCount = $cashInHandList->where('status', 'neutral')->count();
    @endphp
    
    <div class="col-md-4">
        <div class="card border-success">
            <div class="card-body text-center">
                <i class="fas fa-check-circle fa-2x text-success"></i>
                <h5 class="mt-2">Has Money</h5>
                <h2 class="text-success">{{ $hasMoneyCount }}</h2>
                <p class="text-muted">Persons with positive balance</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card border-danger">
            <div class="card-body text-center">
                <i class="fas fa-times-circle fa-2x text-danger"></i>
                <h5 class="mt-2">Overspent</h5>
                <h2 class="text-danger">{{ $overspentCount }}</h2>
                <p class="text-muted">Persons who spent more than received</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card border-secondary">
            <div class="card-body text-center">
                <i class="fas fa-equals fa-2x text-secondary"></i>
                <h5 class="mt-2">Balanced</h5>
                <h2 class="text-secondary">{{ $balancedCount }}</h2>
                <p class="text-muted">Persons with zero balance</p>
            </div>
        </div>
    </div>
</div>

<!-- Legend -->
<div class="card mt-4">
    <div class="card-header bg-info text-white">
        <h5><i class="fas fa-info-circle"></i> Report Legend</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="alert alert-success">
                    <strong><i class="fas fa-check-circle"></i> Has Money:</strong> Received more than spent
                    <br><small>Cash in Hand > $0 (Positive balance)</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="alert alert-danger">
                    <strong><i class="fas fa-times-circle"></i> Overspent:</strong> Spent more than received
                    <br><small>Cash in Hand < $0 (Negative balance)</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="alert alert-secondary">
                    <strong><i class="fas fa-equals"></i> Balanced:</strong> Received equals spent
                    <br><small>Cash in Hand = $0 (Zero balance)</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
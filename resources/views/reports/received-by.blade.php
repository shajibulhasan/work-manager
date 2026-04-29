@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-hand-holding-usd"></i> Received By Report</h2>
    <a href="{{ route('reports.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Reports
    </a>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <h5>Total Receivers</h5>
                <h3>{{ $receivedByList->count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h5>Total Amount Received</h5>
                <h3>${{ number_format($receivedByList->sum('total_amount'), 2) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <h5>Total Transactions</h5>
                <h3>{{ $receivedByList->sum('total_count') }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <h5>Avg Per Transaction</h5>
                <h3>${{ number_format($receivedByList->avg('average_amount'), 2) }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header bg-info text-white">
        <h5><i class="fas fa-table"></i> Money Received By Each Person</h5>
    </div>
    <div class="card-body">
        @if($receivedByList->count() > 0)
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Receiver Name</th>
                        <th>Total Amount</th>
                        <th>Total Count</th>
                        <th>Average</th>
                        <th>Max Amount</th>
                        <th>Min Amount</th>
                        <th>Percentage</th>
                    </tr>
                </thead>
                <tbody>
                    @php $grandTotal = $receivedByList->sum('total_amount'); @endphp
                    @foreach($receivedByList as $item)
                    <tr>
                        <td><strong>{{ $item->received_by }}</strong></td>
                        <td class="text-success">${{ number_format($item->total_amount, 2) }}</td>
                        <td>{{ $item->total_count }}</td>
                        <td>${{ number_format($item->average_amount, 2) }}</td>
                        <td>${{ number_format($item->max_amount, 2) }}</td>
                        <td>${{ number_format($item->min_amount, 2) }}</td>
                        <td>
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar bg-success" 
                                     style="width: {{ $grandTotal > 0 ? ($item->total_amount / $grandTotal) * 100 : 0 }}%">
                                    {{ $grandTotal > 0 ? number_format(($item->total_amount / $grandTotal) * 100, 1) : 0 }}%
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-dark">
                    <tr>
                        <th>Total</th>
                        <th class="text-success">${{ number_format($grandTotal, 2) }}</th>
                        <th>{{ $receivedByList->sum('total_count') }}</th>
                        <th colspan="4"></th>
                    </tr>
                </tfoot>
            </table>
        </div>
        @else
        <div class="text-center py-4">
            <i class="fas fa-inbox fa-3x text-muted"></i>
            <p class="mt-2">No data available</p>
        </div>
        @endif
    </div>
</div>
@endsection
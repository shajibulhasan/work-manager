@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5><i class="fas fa-eye"></i> Deposit Details</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">Amount</th>
                        <td>${{ number_format($deposit->amount, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Depositor Name</th>
                        <td>{{ $deposit->depositor_name }}</td>
                    </tr>
                    <tr>
                        <th>Received By</th>
                        <td>{{ $deposit->received_by }}</td>
                    </tr>
                    <tr>
                        <th>Deposit Date</th>
                        <td>{{ $deposit->deposit_date->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <th>Purpose</th>
                        <td>{{ $deposit->purpose ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>{{ $deposit->created_at->format('d M Y, h:i A') }}</td>
                    </tr>
                    <tr>
                        <th>Last Updated</th>
                        <td>{{ $deposit->updated_at->format('d M Y, h:i A') }}</td>
                    </tr>
                </table>
                
                <div class="mt-3">
                    <a href="{{ route('deposits.edit', $deposit) }}" class="btn btn-danger">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="{{ route('deposits.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5><i class="fas fa-plus"></i> Add New Deposit</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('deposits.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="amount" class="form-label">Amount ($) *</label>
                            <input type="number" step="0.01" 
                                   class="form-control @error('amount') is-invalid @enderror" 
                                   id="amount" name="amount" value="{{ old('amount') }}" required>
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="deposit_date" class="form-label">Deposit Date *</label>
                            <input type="date" 
                                   class="form-control @error('deposit_date') is-invalid @enderror" 
                                   id="deposit_date" name="deposit_date" 
                                   value="{{ old('deposit_date', date('Y-m-d')) }}" required>
                            @error('deposit_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="depositor_name" class="form-label">Depositor Name *</label>
                            <input type="text" 
                                   class="form-control @error('depositor_name') is-invalid @enderror" 
                                   id="depositor_name" name="depositor_name" 
                                   value="{{ old('depositor_name') }}" required>
                            @error('depositor_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="received_by" class="form-label">Received By *</label>
                            <input type="text" 
                                   class="form-control @error('received_by') is-invalid @enderror" 
                                   id="received_by" name="received_by" 
                                   value="{{ old('received_by') }}" required>
                            @error('received_by')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="purpose" class="form-label">Purpose</label>
                        <textarea class="form-control @error('purpose') is-invalid @enderror" 
                                  id="purpose" name="purpose" rows="3">{{ old('purpose') }}</textarea>
                        @error('purpose')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Save Deposit
                    </button>
                    <a href="{{ route('deposits.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
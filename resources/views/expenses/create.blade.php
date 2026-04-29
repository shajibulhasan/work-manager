@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5><i class="fas fa-plus"></i> Add New Expense</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('expenses.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="amount" class="form-label">Amount (Tk) *</label>
                            <input type="number" step="0.01" 
                                   class="form-control @error('amount') is-invalid @enderror" 
                                   id="amount" name="amount" value="{{ old('amount') }}" required>
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="expense_date" class="form-label">Expense Date *</label>
                            <input type="date" 
                                   class="form-control @error('expense_date') is-invalid @enderror" 
                                   id="expense_date" name="expense_date" 
                                   value="{{ old('expense_date', date('Y-m-d')) }}" required>
                            @error('expense_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="expense_category" class="form-label">Category *</label>
                        <select class="form-select @error('expense_category') is-invalid @enderror" 
                                id="expense_category" name="expense_category" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->name }}" 
                                        {{ old('expense_category') == $category->name ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('expense_category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="spent_by" class="form-label">Spent By *</label>
                            <input type="text" 
                                   class="form-control @error('spent_by') is-invalid @enderror" 
                                   id="spent_by" name="spent_by" 
                                   value="{{ old('spent_by') }}" required>
                            @error('spent_by')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="spent_at" class="form-label">Spent At (Location) *</label>
                            <input type="text" 
                                   class="form-control @error('spent_at') is-invalid @enderror" 
                                   id="spent_at" name="spent_at" 
                                   value="{{ old('spent_at') }}" required>
                            @error('spent_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="paid_to" class="form-label">Paid To</label>
                            <input type="text" 
                                   class="form-control @error('paid_to') is-invalid @enderror" 
                                   id="paid_to" name="paid_to" 
                                   value="{{ old('paid_to') }}">
                            @error('paid_to')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Save Expense
                    </button>
                    <a href="{{ route('expenses.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
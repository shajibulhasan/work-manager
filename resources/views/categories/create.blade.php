@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5><i class="fas fa-plus"></i> Create New Category</h5>
            </div>
            <div class="card-body">
                {{-- Main error display --}}
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <strong>Please fix the following errors:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('categories.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Category Name *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="type" class="form-label">Category Type *</label>
                        <select class="form-select @error('type') is-invalid @enderror" 
                                id="type" name="type" required>
                            <option value="">Select Type</option>
                            <option value="expense" {{ old('type') == 'expense' ? 'selected' : '' }}>
                                Expense Category
                            </option>
                            <option value="task" {{ old('type') == 'task' ? 'selected' : '' }}>
                                Task Category
                            </option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Select Color *</label>
                        <div class="row">
                            @foreach($colors as $code => $name)
                            <div class="col-md-2 col-4 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="color" 
                                           id="color_{{ $loop->index }}" value="{{ $code }}"
                                           {{ old('color', '#3490dc') == $code ? 'checked' : '' }}>
                                    <label class="form-check-label" for="color_{{ $loop->index }}">
                                        <span class="badge" style="background-color: {{ $code }}; width: 50px;">
                                            &nbsp;&nbsp;&nbsp;
                                        </span>
                                        <br><small>{{ $name }}</small>
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @error('color')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Select Icon *</label>
                        <div class="row">
                            @foreach($icons as $icon => $name)
                            <div class="col-md-3 col-6 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="icon" 
                                           id="icon_{{ $loop->index }}" value="{{ $icon }}"
                                           {{ old('icon', 'fas fa-tag') == $icon ? 'checked' : '' }}>
                                    <label class="form-check-label" for="icon_{{ $loop->index }}">
                                        <i class="{{ $icon }} fa-2x" style="color: {{ old('color', '#3490dc') }}"></i>
                                        <br><small>{{ $name }}</small>
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @error('icon')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="2">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active"
                               {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Active</label>
                    </div>
                    
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Save Category
                    </button>
                    <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
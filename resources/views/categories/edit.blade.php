@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5>
                    <i class="fas fa-edit"></i> 
                    Edit Category: 
                    <span>
                        {{ $category->name }}
                    </span>
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('categories.update', $category) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Category Name *</label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" 
                               value="{{ old('name', $category->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="type" class="form-label">Category Type *</label>
                        <select class="form-select @error('type') is-invalid @enderror" 
                                id="type" name="type" required>
                            <option value="">Select Type</option>
                            <option value="expense" {{ old('type', $category->type) == 'expense' ? 'selected' : '' }}>
                                Expense Category
                            </option>
                            <option value="task" {{ old('type', $category->type) == 'task' ? 'selected' : '' }}>
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
                            <div class="col-md-3 col-6 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="color" 
                                           id="color_{{ $loop->index }}" value="{{ $code }}"
                                           {{ old('color', $category->color) == $code ? 'checked' : '' }}>
                                    <label class="form-check-label" for="color_{{ $loop->index }}">
                                        <span class="badge p-2" style="background-color: {{ $code }}; width: 60px; display: inline-block;">
                                            &nbsp;
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
                                           {{ old('icon', $category->icon) == $icon ? 'checked' : '' }}>
                                    <label class="form-check-label" for="icon_{{ $loop->index }}">
                                        <i class="{{ $icon }} fa-2x" style="color: {{ old('color', $category->color) }}"></i>
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
                                  id="description" name="description" rows="2">{{ old('description', $category->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active"
                               {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Active</label>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Update Category
                        </button>
                        
                        <div>
                            <a href="{{ route('categories.show', $category) }}" class="btn btn-info">
                                <i class="fas fa-eye"></i> View Details
                            </a>
                            <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@if($category->expenses()->count() > 0 || $category->tasks()->count() > 0)
<div class="row mt-4">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h6><i class="fas fa-info-circle"></i> Usage Information</h6>
            </div>
            <div class="card-body">
                <p>This category is currently being used by:</p>
                <ul>
                    @if($category->expenses()->count() > 0)
                        <li><strong>{{ $category->expenses()->count() }}</strong> expenses</li>
                    @endif
                    @if($category->tasks()->count() > 0)
                        <li><strong>{{ $category->tasks()->count() }}</strong> tasks</li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
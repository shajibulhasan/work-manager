@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-tags"></i> Category Management</h2>
    <a href="{{ route('categories.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add New Category
    </a>
</div>



<div class="row">
    <!-- Expense Categories -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-danger text-white">
                <h5><i class="fas fa-arrow-up"></i> Expense Categories</h5>
            </div>
            <div class="card-body">
                @if($expenseCategories->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($expenseCategories as $category)
                            <tr>
                                <td>
                                    <span style="color: {{ $category->color }}">
                                        <i class="{{ $category->icon }} fa-lg"></i>
                                    </span>
                                    <strong>{{ $category->name }}</strong>
                                    @if($category->description)
                                        <br><small class="text-muted">{{ $category->description }}</small>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-sm toggle-status" 
                                            data-category-id="{{ $category->id }}"
                                            style="width: 80px;">
                                        @if($category->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </button>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('categories.show', $category) }}" 
                                           class="btn btn-info btn-sm" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('categories.edit', $category) }}" 
                                           class="btn btn-warning btn-sm" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger btn-sm" 
                                                onclick="confirmDelete({{ $category->id }}, '{{ $category->name }}')"
                                                title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    <form id="delete-form-{{ $category->id }}" 
                                          action="{{ route('categories.destroy', $category) }}" 
                                          method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-3">
                    <i class="fas fa-inbox fa-2x text-muted"></i>
                    <p class="mt-2">No expense categories found.</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Task Categories -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5><i class="fas fa-tasks"></i> Task Categories</h5>
            </div>
            <div class="card-body">
                @if($taskCategories->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($taskCategories as $category)
                            <tr>
                                <td>
                                    <span style="color: {{ $category->color }}">
                                        <i class="{{ $category->icon }} fa-lg"></i>
                                    </span>
                                    <strong>{{ $category->name }}</strong>
                                    @if($category->description)
                                        <br><small class="text-muted">{{ $category->description }}</small>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-sm toggle-status" 
                                            data-category-id="{{ $category->id }}"
                                            style="width: 80px;">
                                        @if($category->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </button>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('categories.show', $category) }}" 
                                           class="btn btn-info btn-sm" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('categories.edit', $category) }}" 
                                           class="btn btn-warning btn-sm" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger btn-sm" 
                                                onclick="confirmDelete({{ $category->id }}, '{{ $category->name }}')"
                                                title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    <form id="delete-form-{{ $category->id }}" 
                                          action="{{ route('categories.destroy', $category) }}" 
                                          method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-3">
                    <i class="fas fa-inbox fa-2x text-muted"></i>
                    <p class="mt-2">No task categories found.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete category: <strong id="deleteCategoryName"></strong>?</p>
                <p class="text-danger">This action cannot be undone!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let categoryToDelete = null;

function confirmDelete(categoryId, categoryName) {
    categoryToDelete = categoryId;
    document.getElementById('deleteCategoryName').textContent = categoryName;
    var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}

document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
    if (categoryToDelete) {
        document.getElementById('delete-form-' + categoryToDelete).submit();
    }
});

// Toggle category status
document.querySelectorAll('.toggle-status').forEach(button => {
    button.addEventListener('click', function() {
        const categoryId = this.dataset.categoryId;
        const badge = this.querySelector('.badge');
        
        fetch(`/categories/${categoryId}/toggle-status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (data.is_active) {
                    badge.className = 'badge bg-success';
                    badge.textContent = 'Active';
                } else {
                    badge.className = 'badge bg-secondary';
                    badge.textContent = 'Inactive';
                }
            }
        })
        .catch(error => console.error('Error:', error));
    });
});
</script>
@endpush
@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header" style="background-color: {{ $category->color }}; color: white;">
                <h5>
                    <i class="{{ $category->icon }}"></i> {{ $category->name }}
                    <span class="float-end">
                        @if($category->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </span>
                </h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">Category Name</th>
                        <td>{{ $category->name }}</td>
                    </tr>
                    <tr>
                        <th>Type</th>
                        <td>
                            <span class="badge bg-{{ $category->type == 'expense' ? 'danger' : 'info' }}">
                                {{ ucfirst($category->type) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Color</th>
                        <td>
                            <span class="badge" style="background-color: {{ $category->color }}">
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            </span>
                            {{ $category->color }}
                        </td>
                    </tr>
                    <tr>
                        <th>Icon</th>
                        <td><i class="{{ $category->icon }} fa-2x" style="color: {{ $category->color }}"></i> {{ $category->icon }}</td>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <td>{{ $category->description ?? 'No description' }}</td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>{{ $category->created_at->format('d M Y, h:i A') }}</td>
                    </tr>
                    <tr>
                        <th>Last Updated</th>
                        <td>{{ $category->updated_at->format('d M Y, h:i A') }}</td>
                    </tr>
                </table>
                
                <div class="mt-3">
                    <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to List
                    </a>
                    <button type="button" class="btn btn-danger float-end" onclick="confirmDelete()">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                    <form id="delete-form" action="{{ route('categories.destroy', $category) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@if($category->type == 'expense' && $expenses->count() > 0)
<div class="row mt-4">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header bg-danger text-white">
                <h6><i class="fas fa-money-bill"></i> Expenses in this Category (Total: ${{ number_format($totalExpenses, 2) }})</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Description</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($expenses as $expense)
                            <tr>
                                <td>{{ $expense->expense_date->format('d M Y') }}</td>
                                <td>{{ $expense->description ?? 'N/A' }}</td>
                                <td>${{ number_format($expense->amount, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@if($category->type == 'task' && $tasks->count() > 0)
<div class="row mt-4">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h6><i class="fas fa-tasks"></i> Tasks in this Category ({{ $completedTasks }}/{{ $totalTasks }} Completed)</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Status</th>
                                <th>Due Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tasks as $task)
                            <tr>
                                <td>{{ $task->title }}</td>
                                <td>
                                    @if($task->status == 'completed')
                                        <span class="badge bg-success">Completed</span>
                                    @elseif($task->status == 'in_progress')
                                        <span class="badge bg-primary">In Progress</span>
                                    @else
                                        <span class="badge bg-warning">Pending</span>
                                    @endif
                                </td>
                                <td>{{ $task->due_date ? $task->due_date->format('d M Y') : 'N/A' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete category: <strong>{{ $category->name }}</strong>?</p>
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
function confirmDelete() {
    var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}

document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
    document.getElementById('delete-form').submit();
});
</script>
@endpush
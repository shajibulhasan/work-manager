@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center" 
                 style="background-color: {{ $task->category ? $task->category->color : '#3490dc' }}; color: white;">
                <h5 class="mb-0">
                    @if($task->category)
                        <i class="{{ $task->category->icon }}"></i>
                    @else
                        <i class="fas fa-tasks"></i>
                    @endif
                    {{ $task->title }}
                </h5>
                <span>
                    @if($task->status == 'completed')
                        <span class="badge bg-danger">Completed</span>
                    @elseif($task->status == 'in_progress')
                        <span class="badge bg-danger">In Progress</span>
                    @elseif($task->status == 'cancelled')
                        <span class="badge bg-danger">Cancelled</span>
                    @else
                        <span class="badge bg-danger">Pending</span>
                    @endif
                </span>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">Task Title</th>
                        <td>{{ $task->title }}</td>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <td>{{ $task->description ?? 'No description provided' }}</td>
                    </tr>
                    <tr>
                        <th>Priority</th>
                        <td>
                            @if($task->priority == 'urgent')
                                <span class="badge bg-danger">Urgent</span>
                            @elseif($task->priority == 'high')
                                <span class="badge bg-warning">High</span>
                            @elseif($task->priority == 'medium')
                                <span class="badge bg-primary">Medium</span>
                            @else
                                <span class="badge bg-success">Low</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            <form action="{{ route('tasks.update-status', $task) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <select name="status" class="form-select form-select-sm" 
                                        onchange="this.form.submit()" style="width: 200px;">
                                    <option value="pending" {{ $task->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="in_progress" {{ $task->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="completed" {{ $task->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ $task->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <th>Category</th>
                        <td>
                            @if($task->category)
                                <span style="color: {{ $task->category->color }}">
                                    <i class="{{ $task->category->icon }} fa-lg"></i>
                                </span>
                                {{ $task->category->name }}
                            @else
                                <span class="text-muted">No category</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Due Date</th>
                        <td>
                            @if($task->due_date)
                                @if($task->isOverdue())
                                    <span class="text-danger fw-bold">
                                        {{ $task->due_date->format('d F Y') }} (Overdue!)
                                    </span>
                                @else
                                    {{ $task->due_date->format('d F Y') }}
                                    <small class="text-muted">
                                        ({{ $task->due_date->diffForHumans() }})
                                    </small>
                                @endif
                            @else
                                <span class="text-muted">No due date set</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Assigned To</th>
                        <td>{{ $task->assigned_to ?? 'Not assigned' }}</td>
                    </tr>
                    <tr>
                        <th>Estimated Cost</th>
                        <td>
                            @if($task->estimated_cost)
                                ${{ number_format($task->estimated_cost, 2) }}
                            @else
                                <span class="text-muted">Not set</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Actual Cost</th>
                        <td>
                            @if($task->actual_cost)
                                ${{ number_format($task->actual_cost, 2) }}
                            @else
                                <span class="text-muted">Not set</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Notes</th>
                        <td>{{ $task->notes ?? 'No notes' }}</td>
                    </tr>
                    <tr>
                        <th>Completed At</th>
                        <td>
                            @if($task->completed_at)
                                {{ $task->completed_at->format('d F Y, h:i A') }}
                            @else
                                <span class="text-muted">Not completed yet</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>{{ $task->created_at->format('d F Y, h:i A') }}</td>
                    </tr>
                    <tr>
                        <th>Last Updated</th>
                        <td>{{ $task->updated_at->format('d F Y, h:i A') }}</td>
                    </tr>
                </table>
                
                <div class="d-flex justify-content-between mt-4">
                    <div>
                        <a href="{{ route('tasks.edit', $task) }}" class="btn btn-info">
                            <i class="fas fa-edit"></i> Edit Task
                        </a>
                        <a href="{{ route('tasks.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Tasks
                        </a>
                    </div>
                    
                    <div>
                        @if($task->status != 'completed')
                            <form action="{{ route('tasks.update-status', $task) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="completed">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check"></i> Mark as Completed
                                </button>
                            </form>
                        @endif
                        
                        <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                            <i class="fas fa-trash"></i> Delete Task
                        </button>
                        <form id="delete-form" action="{{ route('tasks.destroy', $task) }}" method="POST" style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        @if($task->status == 'completed')
        <div class="card mt-3 border-success">
            <div class="card-body text-center">
                <i class="fas fa-check-circle fa-3x text-success"></i>
                <h5 class="mt-2 text-success">Task Completed!</h5>
                <p class="text-muted">This task was completed on {{ $task->completed_at->format('d F Y') }}.</p>
            </div>
        </div>
        @endif
        
        @if($task->isOverdue())
        <div class="card mt-3 border-danger">
            <div class="card-body text-center">
                <i class="fas fa-exclamation-triangle fa-3x text-danger"></i>
                <h5 class="mt-2 text-danger">Task is Overdue!</h5>
                <p class="text-muted">This task was due on {{ $task->due_date->format('d F Y') }} and needs immediate attention.</p>
            </div>
        </div>
        @endif
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
                <p>Are you sure you want to delete this task?</p>
                <p><strong>Task:</strong> {{ $task->title }}</p>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Quick status update via AJAX
    document.querySelectorAll('.status-update').forEach(select => {
        select.addEventListener('change', function() {
            const taskId = this.dataset.taskId;
            const status = this.value;
            const originalValue = this.getAttribute('data-original') || this.value;
            
            // Show loading state
            this.disabled = true;
            
            fetch(`/tasks/${taskId}/status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ status: status })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Show success notification (optional)
                    const alert = document.createElement('div');
                    alert.className = 'alert alert-success alert-dismissible fade show position-fixed';
                    alert.style.cssText = 'top: 20px; right: 20px; z-index: 9999;';
                    alert.innerHTML = `
                        Status updated successfully!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    `;
                    document.body.appendChild(alert);
                    
                    // Auto remove after 3 seconds
                    setTimeout(() => alert.remove(), 3000);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Revert to original value on error
                select.value = originalValue;
                alert('Failed to update status. Please try again.');
            })
            .finally(() => {
                this.disabled = false;
            });
        });
    });
});
</script>
@endpush
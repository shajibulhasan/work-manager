@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-tasks"></i> Tasks</h2>
    <a href="{{ route('tasks.create') }}" class="btn btn-success">
        <i class="fas fa-plus"></i> Add New Task
    </a>
</div>

<!-- Task Stats -->
<div class="row mb-4">
    <div class="col-md-2 col-6 mb-2">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <h3>{{ $taskStats['total'] }}</h3>
                <small>Total</small>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-6 mb-2">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <h3>{{ $taskStats['pending'] }}</h3>
                <small>Pending</small>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-6 mb-2">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <h3>{{ $taskStats['in_progress'] }}</h3>
                <small>In Progress</small>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-6 mb-2">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h3>{{ $taskStats['completed'] }}</h3>
                <small>Completed</small>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-6 mb-2">
        <div class="card bg-danger text-white">
            <div class="card-body text-center">
                <h3>{{ $taskStats['overdue'] }}</h3>
                <small>Overdue</small>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('tasks.index') }}" method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select class="form-select" name="status">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Priority</label>
                <select class="form-select" name="priority">
                    <option value="">All Priorities</option>
                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                    <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Category</label>
                <select class="form-select" name="category_id">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" 
                                {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Search</label>
                <input type="text" class="form-control" name="search" 
                       placeholder="Search tasks..." value="{{ request('search') }}">
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <a href="{{ route('tasks.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Clear
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Tasks List -->
<div class="card">
    <div class="card-body">
        @if($tasks->count() > 0)
        <div class="table-responsive">
            <table class="table">
                <thead class="table-success">
                    <tr>
                        <th>Title</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Category</th>
                        <th>Due Date</th>
                        <th>Assigned To</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tasks as $task)
                    <tr class="{{ $task->isOverdue() ? 'table-danger' : '' }}">
                        <td>
                            <strong>{{ $task->title }}</strong>
                            @if($task->estimated_cost)
                                <br><small class="text-muted">Est. Cost: ${{ number_format($task->estimated_cost, 2) }}</small>
                            @endif
                        </td>
                        <td>
                            <span class="priority-{{ $task->priority }}">
                                {{ ucfirst($task->priority) }}
                            </span>
                        </td>
                        <td>
                            <select class="form-select form-select-sm status-update" 
                                    data-task-id="{{ $task->id }}" 
                                    style="width: 130px;">
                                <option value="pending" {{ $task->status == 'pending' ? 'selected' : '' }}>
                                    Pending
                                </option>
                                <option value="in_progress" {{ $task->status == 'in_progress' ? 'selected' : '' }}>
                                    In Progress
                                </option>
                                <option value="completed" {{ $task->status == 'completed' ? 'selected' : '' }}>
                                    Completed
                                </option>
                                <option value="cancelled" {{ $task->status == 'cancelled' ? 'selected' : '' }}>
                                    Cancelled
                                </option>
                            </select>
                        </td>
                        <td>
                            @if($task->category)
                                <span style="color: {{ $task->category->color }}">
                                    <i class="{{ $task->category->icon }}"></i>
                                </span>
                                {{ $task->category->name }}
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td>
                            @if($task->due_date)
                                @if($task->isOverdue())
                                    <span class="text-danger">
                                        {{ $task->due_date->format('d M Y') }}
                                    </span>
                                @else
                                    {{ $task->due_date->format('d M Y') }}
                                @endif
                            @else
                                <span class="text-muted">No date</span>
                            @endif
                        </td>
                        <td>{{ $task->assigned_to ?? 'N/A' }}</td>
                        <td>
                            <a href="{{ route('tasks.show', $task) }}" 
                               class="btn btn-info btn-sm" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('tasks.edit', $task) }}" 
                               class="btn btn-warning btn-sm" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('tasks.destroy', $task) }}" 
                                  method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" 
                                        onclick="return confirm('Are you sure?')"
                                        title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $tasks->links() }}
        @else
        <div class="text-center py-4">
            <i class="fas fa-clipboard-list fa-3x text-muted"></i>
            <p class="mt-2">No tasks found. Create your first task!</p>
            <a href="{{ route('tasks.create') }}" class="btn btn-success">Create Task</a>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Quick status update
    document.querySelectorAll('.status-update').forEach(select => {
        select.addEventListener('change', function() {
            const taskId = this.dataset.taskId;
            const status = this.value;
            
            fetch(`/tasks/${taskId}/status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ status: status })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
});
</script>
@endpush
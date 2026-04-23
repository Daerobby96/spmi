@extends('layouts.app')

@section('title', 'Activity Log')
@section('page-title', 'Activity Log')
@section('page-subtitle', 'Track all user activities and system changes.')

@section('breadcrumb')
    <li class="breadcrumb-item active">Activity Log</li>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm border-primary card-header-custom">
            <div class="card-body">
                <form action="{{ route('activity-log.index') }}" method="GET" class="row g-3">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0">
                                <i class="bi bi-search text-muted"></i>
                            </span>
                            <input type="text" name="search" class="form-control border-start-0" 
                                   placeholder="Search by description, action, or user..." 
                                   value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select name="user_id" class="form-select">
                            <option value="">-- All Users --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="action" class="form-select">
                            <option value="">-- All Actions --</option>
                            <option value="created" {{ request('action') == 'created' ? 'selected' : '' }}>Created</option>
                            <option value="updated" {{ request('action') == 'updated' ? 'selected' : '' }}>Updated</option>
                            <option value="deleted" {{ request('action') == 'deleted' ? 'selected' : '' }}>Deleted</option>
                            <option value="login" {{ request('action') == 'login' ? 'selected' : '' }}>Login</option>
                            <option value="logout" {{ request('action') == 'logout' ? 'selected' : '' }}>Logout</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                             Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Timestamp</th>
                        <th>User</th>
                        <th>Action</th>
                        <th>Description</th>
                        <th>IP Address</th>
                        <th class="pe-4">Details</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td class="ps-4">
                                <span class="d-block small fw-bold">{{ $log->created_at->translatedFormat('d F Y') }}</span>
                                <span class="text-muted small">{{ $log->created_at->format('H:i:s') }}</span>
                            </td>
                            <td>
                                @if($log->user)
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-2 text-white bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 12px;">
                                            {{ strtoupper(substr($log->user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold small">{{ $log->user->name }}</div>
                                            <div class="text-muted" style="font-size: 11px;">{{ $log->user->role->name ?? '-' }}</div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted italic small">System</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $badgeClass = match($log->action) {
                                        'created' => 'bg-success',
                                        'updated' => 'bg-info',
                                        'deleted' => 'bg-danger',
                                        'login'   => 'bg-primary',
                                        'logout'  => 'bg-secondary',
                                        default   => 'bg-dark'
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ strtoupper($log->action) }}</span>
                            </td>
                            <td>
                                <span class="small">{{ $log->description }}</span>
                                <div class="text-muted smaller mt-1" style="font-size: 11px;">
                                    {{ class_basename($log->model_type) }} #{{ $log->model_id }}
                                </div>
                            </td>
                            <td>
                                <code class="small">{{ $log->ip_address }}</code>
                            </td>
                            <td class="pe-4">
                                @if($log->properties)
                                    <button type="button" class="btn btn-sm btn-outline-primary" 
                                            data-bs-toggle="modal" data-bs-target="#modal-log-{{ $log->id }}">
                                        View Data
                                    </button>
                                    
                                    <!-- Modal -->
                                    <div class="modal fade" id="modal-log-{{ $log->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content border-0 shadow">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Activity Detail</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body bg-light">
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <div class="fw-bold mb-2">Metadata</div>
                                                            <table class="table table-sm table-bordered bg-white small">
                                                                <tr><th>User</th><td>{{ $log->user->name ?? 'System' }}</td></tr>
                                                                <tr><th>Action</th><td>{{ strtoupper($log->action) }}</td></tr>
                                                                <tr><th>Model</th><td>{{ $log->model_type }}</td></tr>
                                                                <tr><th>IP Address</th><td>{{ $log->ip_address }}</td></tr>
                                                                <tr><th>Browser</th><td><span class="text-wrap">{{ $log->user_agent }}</span></td></tr>
                                                            </table>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="fw-bold mb-2">Data Changes</div>
                                                            <pre class="bg-dark text-light p-3 rounded small" style="max-height: 300px; overflow: auto;">{{ json_encode($log->properties, JSON_PRETTY_PRINT) }}</pre>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted small">No logs found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4">
    {{ $logs->links() }}
</div>
@endsection

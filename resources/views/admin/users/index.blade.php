@extends('dashboard')

@section('content')
<div class="container py-4">
    <h2 class="mb-3">User Management</h2>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="GET" action="{{ route('web.admin.users.index') }}" class="row g-2 mb-3">
        <div class="col-md-5">
            <input
                type="text"
                name="search"
                value="{{ $search }}"
                class="form-control"
                placeholder="Search by name or email"
            >
        </div>
        <div class="col-md-4">
            <select name="role" class="form-select">
                <option value="">All Roles</option>
                @foreach ($roles as $role)
                    <option value="{{ $role }}" @selected($selectedRole === $role)>
                        {{ ucfirst($role) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <button class="btn btn-primary w-100" type="submit">Filter</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Current Role</th>
                    <th>Created</th>
                    <th style="min-width: 220px;">Update Role</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $managedUser)
                    <tr>
                        <td>{{ $managedUser->name }}</td>
                        <td>{{ $managedUser->email }}</td>
                        <td><span class="badge bg-secondary">{{ ucfirst($managedUser->role) }}</span></td>
                        <td>{{ $managedUser->created_at?->format('Y-m-d H:i') }}</td>
                        <td>
                            <form method="POST" action="{{ route('web.admin.users.update-role', $managedUser) }}" class="d-flex gap-2">
                                @csrf
                                @method('PATCH')
                                <select name="role" class="form-select form-select-sm">
                                    @foreach ($roles as $role)
                                        <option value="{{ $role }}" @selected($managedUser->role === $role)>
                                            {{ ucfirst($role) }}
                                        </option>
                                    @endforeach
                                </select>
                                <button class="btn btn-sm btn-success" type="submit">Save</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $users->links() }}
</div>
@endsection

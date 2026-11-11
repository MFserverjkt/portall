@extends('layouts.app')

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold m-0"><i class="bi bi-people me-2 text-primary"></i> User Management</h3>
            <p class="text-muted small mb-0">Kelola pengguna dan hak akses berdasarkan lokasi cabang Maison Feerie.</p>
        </div>
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            <i class="bi bi-person-plus me-1"></i> Tambah User Baru
        </a>
    </div>

    <!-- Filter Manual (Cabang & Role) -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('users.index') }}" method="GET" class="row g-3 align-items-end">
                
                <!-- Filter Cabang / Outlet -->
                <div class="col-md-5">
                    <label class="form-label small fw-bold">Filter Cabang / Outlet</label>
                    <select name="branch" class="form-select">
                        <option value="">-- Semua Cabang --</option>
                        @foreach($branches as $code => $name)
                            <option value="{{ $code }}" {{ request('branch') == $code ? 'selected' : '' }}>
                                [{{ $code }}] {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Role -->
                <div class="col-md-4">
                    <label class="form-label small fw-bold">Filter Role</label>
                    <select name="role" class="form-select">
                        <option value="">-- Semua Role --</option>
                        @foreach($roles ?? [] as $r)
                            <option value="{{ $r }}" {{ request('role') == $r ? 'selected' : '' }}>
                                {{ $r }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tombol Aksi Filter -->
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary me-1">
                        <i class="bi bi-filter"></i> Filter
                    </button>
                    @if(request('branch') || request('role'))
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-sync"></i> Reset
                        </a>
                    @endif
                </div>

            </form>
        </div>
    </div>

    <!-- Tabel User (Dilengkapi Scroll & Sticky Header) -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive" style="max-height: 550px; overflow-y: auto;">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light sticky-top" style="z-index: 1;">
                        <tr>
                            <th>#</th>
                            <th>Nama User</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Cabang / Outlet</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>{{ method_exists($users, 'firstItem') ? $users->firstItem() + $loop->index : $loop->iteration }}</td>
                                <td class="fw-semibold">{{ $user->name }}</td>
                                <td>{{ $user->username }}</td>
                                <td>
                                    <span class="badge bg-{{ $user->role === 'ADMIN' ? 'danger' : ($user->role === 'IT' ? 'info text-dark' : ($user->role === 'MAINTENANCE' ? 'warning text-dark' : 'secondary')) }}">
                                        {{ $user->role }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        <i class="bi bi-geo-alt-fill text-danger me-1"></i>{{ $user->branch_name }} ({{ $user->branch_code }})
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-outline-warning me-1">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Data user tidak ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Pagination Links -->
        @if(method_exists($users, 'hasPages') && $users->hasPages())
            <div class="card-footer bg-white py-3">
                {{ $users->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
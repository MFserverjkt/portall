@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0">
            <i class="bi bi-shield-lock me-2"></i>Pengaturan Hak Akses Role
        </h4>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- CARD TABEL ROLE --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 60px;">No</th>
                            <th style="width: 200px;">Nama Role</th>
                            <th>Hak Akses (Permissions)</th>
                            <th class="text-center" style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($roles as $index => $role)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>
                                    <span class="fw-bold text-primary text-uppercase">{{ $role->name }}</span>
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        @forelse($role->permissions as $perm)
                                            <span class="badge bg-info text-dark">{{ $perm->name }}</span>
                                        @empty
                                            <span class="text-muted small"><em>Belum ada permission assigned</em></span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $role->id }}">
                                        <i class="bi bi-pencil-square me-1"></i> Edit Akses
                                    </button>
                                </td>
                            </tr>

                            {{-- MODAL EDIT PERMISSION --}}
                            <div class="modal fade" id="editModal{{ $role->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content">
                                        <form action="{{ route('roles.update', $role->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header bg-dark text-white py-2">
                                                <h6 class="modal-title fw-bold">Edit Akses: {{ strtoupper($role->name) }}</h6>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row g-3">
                                                    @forelse($permissions as $permission)
                                                        <div class="col-md-4 col-6">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->name }}" id="perm_{{ $role->id }}_{{ $permission->id }}"
                                                                {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                                                <label class="form-check-label small" for="perm_{{ $role->id }}_{{ $permission->id }}">
                                                                    {{ $permission->name }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    @empty
                                                        <div class="col-12 text-muted">Belum ada data permission di database.</div>
                                                    @endforelse
                                                </div>
                                            </div>
                                            <div class="modal-footer py-2">
                                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">
                                    Data Role belum ada di database.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
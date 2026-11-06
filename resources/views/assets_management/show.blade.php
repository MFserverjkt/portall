@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Detail Asset: {{ $asset->asset_name }}</h2>
        <div>
            <a href="#" class="btn btn-outline-dark btn-sm me-2">Ekspor Excel</a>
            <a href="#" class="btn btn-outline-dark btn-sm me-2">Ekspor PDF</a>
            <a href="{{ route('asset-management.index') }}" class="btn btn-secondary btn-sm">KEMBALI</a>
        </div>
    </div>

    <!-- Data Asset Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="small text-muted fw-bold">TANGGAL ASSET BARU</label>
                    <input type="text" class="form-control bg-light" value="{{ $asset->purchase_date }}" readonly>
                </div>
                <div class="col-md-3">
                    <label class="small text-muted fw-bold">KONDISI</label>
                    <input type="text" class="form-control bg-light" value="{{ $asset->condition }}" readonly>
                </div>
                <div class="col-md-3">
                    <label class="small text-muted fw-bold">STATUS ASSET</label>
                    <input type="text" class="form-control bg-light" value="{{ $asset->status_asset }}" readonly>
                </div>
                <div class="col-md-3">
                    <label class="small text-muted fw-bold">TANGGAL TERDAFTAR</label>
                    <input type="text" class="form-control bg-light" value="{{ $asset->created_at->format('Y-m-d') }}" readonly>
                </div>
            </div>
        </div>
    </div>

    <!-- Riwayat Pengguna -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0 fw-bold">Riwayat Pengguna</h5>
            <button class="btn btn-primary btn-sm fw-bold" data-bs-toggle="modal" data-bs-target="#modalCreatePengguna">CREATE PENGGUNA</button>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th class="ps-4">TANGGAL</th>
                        <th>KODE ASET</th>
                        <th>LOKASI</th>
                        <th>PENGGUNA</th>
                        <th>KETERANGAN</th>
                        <th>PETUGAS</th>
                        <th class="text-end pe-4">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($asset->userHistories as $history)
                    <tr>
                        <td class="ps-4">{{ $history->usage_date }}</td>
                        <td>{{ $history->asset_code }}</td>
                        <td>{{ $history->location }}</td>
                        <td>{{ $history->new_user }}</td>
                        <td>{{ $history->description ?? '-' }}</td>
                        <td>{{ $history->officer }}</td>
                        <td class="text-end pe-4">
                            <span class="badge bg-success rounded-circle p-2"></span>
                            <span class="badge bg-warning rounded-circle p-2"></span>
                            <span class="badge bg-danger rounded-circle p-2"></span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-3 text-muted">Belum ada riwayat pengguna.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Riwayat Pemeliharaan -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0 fw-bold">Riwayat Pemeliharaan</h5>
            <button class="btn btn-primary btn-sm fw-bold" data-bs-toggle="modal" data-bs-target="#modalCreateRiwayat">CREATE RIWAYAT</button>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th class="ps-4">TANGGAL</th>
                        <th>KODE ASET</th>
                        <th>LOKASI</th>
                        <th>PENGGUNA</th>
                        <th>KETERANGAN</th>
                        <th>PETUGAS</th>
                        <th class="text-end pe-4">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($asset->maintenanceHistories as $maint)
                    <tr>
                        <td class="ps-4">{{ $maint->maintenance_date }}</td>
                        <td>{{ $maint->asset_code }}</td>
                        <td>{{ $maint->location }}</td>
                        <td>{{ $maint->user_name }}</td>
                        <td>{{ $maint->description ?? '-' }}</td>
                        <td>{{ $maint->officer }}</td>
                        <td class="text-end pe-4">
                            <span class="badge bg-success rounded-circle p-2"></span>
                            <span class="badge bg-warning rounded-circle p-2"></span>
                            <span class="badge bg-danger rounded-circle p-2"></span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-3 text-muted">Belum ada riwayat pemeliharaan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Create Pengguna -->
<div class="modal fade" id="modalCreatePengguna" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold">CREATE PENGGUNA</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('asset-management.user-history.store', $asset->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">TANGGAL PEMAKAIAN</label>
                        <input type="date" name="usage_date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">KODE ASSET</label>
                        <input type="text" class="form-control bg-light" value="{{ $asset->asset_code }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">LOKASI</label>
                        <input type="text" name="location" class="form-control" required>
                    </div>

                    @php
                        $latestHistory = $asset->userHistories()->latest()->first();
                        $lastUserName = $latestHistory ? $latestHistory->new_user : null;
                    @endphp

                    @if($lastUserName)
                    <div class="mb-3">
                        <label class="form-label small fw-bold">NAMA PENGGUNA LAMA</label>
                        <input type="text" name="old_user" class="form-control bg-light" value="{{ $lastUserName }}" readonly>
                    </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label small fw-bold">NAMA PENGGUNA BARU</label>
                        <input type="text" name="new_user" class="form-control" placeholder="Masukkan nama pengguna baru..." required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">KETERANGAN</label>
                        <input type="text" name="description" class="form-control" placeholder="Keterangan tambahan...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">PETUGAS</label>
                        <input type="text" name="officer" class="form-control" placeholder="Nama petugas..." required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">KEMBALI</button>
                    <button type="submit" class="btn btn-primary">SIMPAN</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Create Riwayat Pemeliharaan -->
<div class="modal fade" id="modalCreateRiwayat" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold">CREATE RIWAYAT</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('asset-management.maintenance-history.store', $asset->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">TANGGAL</label>
                        <input type="date" name="maintenance_date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">JENIS</label>
                        <select name="type" class="form-select" required>
                            <option value="PREVENTIF">PREVENTIF</option>
                            <option value="UPGRADE">UPGRADE</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">KOMPONEN</label>
                        <input type="text" name="component" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">LOKASI</label>
                        <input type="text" name="location" class="form-control" required>
                    </div>

                    @php
                        $activeUser = $asset->userHistories()->latest()->first();
                        $currentUserName = $activeUser ? $activeUser->new_user : '';
                    @endphp

                    <div class="mb-3">
                        <label class="form-label small fw-bold">PENGGUNA</label>
                        <input type="text" name="user_name" class="form-control bg-light" value="{{ $currentUserName }}" readonly required>
                        <small class="text-muted" style="font-size: 0.75rem;">*Otomatis terisi dari riwayat pengguna aktif.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">SPESIFIKASI BARU</label>
                        <input type="text" name="new_specification" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">KETERANGAN</label>
                        <input type="text" name="description" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">PETUGAS</label>
                        <input type="text" name="officer" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">KEMBALI</button>
                    <button type="submit" class="btn btn-primary">SIMPAN</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
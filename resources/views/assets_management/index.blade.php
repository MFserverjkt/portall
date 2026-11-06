@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Asset</h2>
        <div>
            <a href="#" class="btn btn-outline-dark btn-sm me-2">Ekspor Excel</a>
            <a href="#" class="btn btn-outline-dark btn-sm me-2">Ekspor PDF</a>
            <a href="{{ route('asset-management.create') }}" class="btn btn-primary btn-sm fw-bold px-3">CREATE ASSET</a>
        </div>
    </div>

    <!-- Filter Box -->
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('asset-management.index') }}">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="--- FILTER KODE, LOKASI, PENGGUNA, PETUGAS ---" onchange="this.form.submit()">
            </form>
        </div>
    </div>

    <!-- Tabel Data Asset -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th class="py-3 ps-4">TANGGAL</th>
                        <th class="py-3">KODE ASET</th>
                        <th class="py-3">LOKASI</th>
                        <th class="py-3">KETERANGAN / NAMA ASSET</th>
                        <th class="py-3 text-end pe-4">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($assets as $asset)
                    <tr>
                        <td class="ps-4">{{ $asset->purchase_date }}</td>
                        <td class="fw-bold">{{ $asset->asset_code }}</td>
                        <td>{{ $asset->status_asset }}</td>
                        <td>{{ $asset->asset_name }}</td>
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('asset-management.show', $asset->id) }}" class="btn btn-sm btn-success rounded-circle" title="Detail"><i class="bi bi-eye"></i></a>
                                <a href="#" class="btn btn-sm btn-warning rounded-circle" title="Edit"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('asset-management.destroy', $asset->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus asset ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger rounded-circle" title="Hapus"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">Belum ada data asset.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
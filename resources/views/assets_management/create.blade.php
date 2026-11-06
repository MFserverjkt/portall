@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card border-0 shadow-sm col-md-6 mx-auto">
        <div class="card-header bg-primary text-white text-center py-3">
            <h5 class="mb-0 fw-bold">CREATE ASSET</h5>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('asset-management.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold small">TANGGAL PEMBELIAN</label>
                    <input type="date" name="purchase_date" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">NAMA ASSET</label>
                    <input type="text" name="asset_name" class="form-control" placeholder="Masukkan nama asset..." required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">KODE ASSET</label>
                    <input type="text" name="asset_code" class="form-control" placeholder="Masukkan kode asset..." required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">STATUS ASSET</label>
                    <input type="text" name="status_asset" class="form-control" placeholder="Contoh: Digunakan / Cadangan" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">TANGGAL DIGUNAKAN</label>
                    <input type="date" name="in_use_date" class="form-control" required>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('asset-management.index') }}" class="btn btn-secondary px-4 fw-bold">KEMBALI</a>
                    <button type="submit" class="btn btn-primary px-4 fw-bold">SIMPAN</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
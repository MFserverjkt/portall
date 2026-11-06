<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Utama Asset
        Schema::create('assets_management', function (Blueprint $table) {
            $table->id();
            $table->date('purchase_date'); // Tanggal Pembelian
            $table->string('asset_name'); // Nama Asset
            $table->string('asset_code')->unique(); // Kode Asset
            $table->string('status_asset'); // Status Asset
            $table->date('in_use_date'); // Tanggal Digunakan
            $table->string('condition')->default('Baik'); // Kondisi
            $table->timestamps();
        });

        // 2. Tabel Riwayat Pengguna (Asset User History)
        Schema::create('asset_user_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained('assets_management')->onDelete('cascade');
            $table->date('usage_date'); // Tanggal Pemakaian
            $table->string('asset_code'); // Kode Asset
            $table->string('location'); // Lokasi
            $table->string('old_user')->nullable(); // Nama Pengguna Lama
            $table->string('new_user'); // Nama Pengguna Baru
            $table->text('description')->nullable(); // Keterangan
            $table->string('officer'); // Petugas
            $table->timestamps();
        });

        // 3. Tabel Riwayat Pemeliharaan (Asset Maintenance History)
        Schema::create('asset_maintenance_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained('assets_management')->onDelete('cascade');
            $table->date('maintenance_date'); // Tanggal
            $table->string('asset_code'); // Kode Asset
            $table->string('location'); // Lokasi
            $table->string('user_name'); // Pengguna
            $table->string('type'); // Jenis (Preventif / Upgrade)
            $table->string('component'); // Komponen
            $table->string('old_specification')->nullable(); // Spesifikasi Lama
            $table->string('new_specification'); // Spesifikasi Baru
            $table->text('description')->nullable(); // Keterangan
            $table->string('officer'); // Petugas
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_maintenance_histories');
        Schema::dropIfExists('asset_user_histories');
        Schema::dropIfExists('assets_management');
    }
};
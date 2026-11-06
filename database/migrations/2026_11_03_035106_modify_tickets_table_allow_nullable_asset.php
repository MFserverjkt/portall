<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            // Ubah asset_id agar boleh kosong (nullable)
            $table->unsignedBigInteger('asset_id')->nullable()->change();
            
            // Tambahkan kolom custom_asset_name jika belum ada
            if (!Schema::hasColumn('tickets', 'custom_asset_name')) {
                $table->string('custom_asset_name')->nullable()->after('asset_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->unsignedBigInteger('asset_id')->nullable(false)->change();
            if (Schema::hasColumn('tickets', 'custom_asset_name')) {
                $table->dropColumn('custom_asset_name');
            }
        });
    }
};
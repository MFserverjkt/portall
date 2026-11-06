<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles; // 1. Import Trait HasRoles dari Spatie

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles; // 2. Aktifkan HasRoles di sini

    /**
     * Kolom yang boleh diisi secara mass-assignment.
     */
    protected $fillable = [
        'name',
        'username',
        'password',
        'role',
        'branch_code',
        'branch_name',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
         * Mengecek apakah user memiliki permission tertentu.
         */
        public function hasPermission($permission)
        {
            // 1. Jika role adalah ADMIN, izinkan semua akses menu
            if ($this->role === 'ADMIN' || $this->hasRole('ADMIN')) {
                return true;
            }

            // 2. Pengecekan via Spatie Permission (disertai penanganan agar tidak error jika permission belum di-seed)
            try {
                if ($this->hasPermissionTo($permission)) {
                    return true;
                }
            } catch (\Throwable $e) {
                // Lanjut ke pengecekan relasi jika permission Spatie tidak ditemukan
            }

            // 3. Fallback: Pengecekan via relasi Role / Custom Permission Table jika aplikasi menggunakannya
            if (method_exists($this, 'permissions') && $this->permissions) {
                return $this->permissions->pluck('name')->contains($permission);
            }

            return false;
        }
}
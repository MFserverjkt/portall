<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    /**
     * Menampilkan daftar role beserta permission-nya.
     */
    public function index()
    {
        // 1. Daftar Role Standar Aplikasi
        $defaultRoles = ['ADMIN', 'IT', 'MAINTENANCE', 'ASSET', 'OUTLET'];

        // 2. Daftar Permission Standar berdasarkan Menu Sidebar
        $defaultPermissions = [
            'user-management',
            'user-role',
            'report-corrective',
            'e-learning',
            'pre-test',
            'post-test',
            'inventori',
            'corrective-tiket',
            'panduan-penggunaan'
        ];

        // 3. Auto-generate Role jika belum ada di DB
        foreach ($defaultRoles as $roleName) {
            Role::firstOrCreate([
                'name'       => $roleName,
                'guard_name' => 'web'
            ]);
        }

        // 4. Auto-generate Permission jika belum ada di DB
        foreach ($defaultPermissions as $permName) {
            Permission::firstOrCreate([
                'name'       => $permName,
                'guard_name' => 'web'
            ]);
        }

        // 5. Ambil data terkini beserta relasinya
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();

        return view('roles.index', compact('roles', 'permissions'));
    }

    /**
     * Memperbarui hak akses (permissions) untuk role tertentu.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'permissions'   => 'nullable|array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        $role = Role::findOrFail($id);

        // Sync permission secara otomatis
        $role->syncPermissions($request->permissions ?? []);

        return redirect()->back()->with('success', "Hak akses untuk role {$role->name} berhasil diperbarui.");
    }

    /**
     * Menambah Role baru (Opsional).
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name',
        ]);

        Role::create([
            'name'       => strtoupper($request->name),
            'guard_name' => 'web'
        ]);

        return redirect()->back()->with('success', 'Role baru berhasil ditambahkan.');
    }
}
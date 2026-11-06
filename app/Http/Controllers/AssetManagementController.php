<?php

namespace App\Http\Controllers;

use App\Models\AssetManagement;
use App\Models\AssetUserHistory;
use App\Models\AssetMaintenanceHistory;
use Illuminate\Http\Request;

class AssetManagementController extends Controller
{
    // Halaman Utama / Indeks Asset
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $assets = AssetManagement::when($search, function($query, $search) {
            $query->where('asset_code', 'like', "%{$search}%")
                  ->orWhere('asset_name', 'like', "%{$search}%")
                  ->orWhere('status_asset', 'like', "%{$search}%");
        })->latest()->paginate(10);

        return view('assets_management.index', compact('assets', 'search'));
    }

    // Form Create Asset
    public function create()
    {
        return view('assets_management.create');
    }

    // Simpan Asset Baru
    public function store(Request $request)
    {
        $request->validate([
            'purchase_date' => 'required|date',
            'asset_name'    => 'required|string|max:255',
            'asset_code'    => 'required|string|unique:assets_management,asset_code',
            'status_asset'  => 'required|string',
            'in_use_date'   => 'required|date',
        ]);

        AssetManagement::create($request->all());

        return redirect()->route('asset-management.index')->with('success', 'Asset baru berhasil disimpan.');
    }

    // Form Edit Asset (Opsional jika dibutuhkan)
    public function edit($id)
    {
        $asset = AssetManagement::findOrFail($id);
        return view('assets_management.edit', compact('asset'));
    }

    // Update Asset
    public function update(Request $request, $id)
    {
        $asset = AssetManagement::findOrFail($id);

        $request->validate([
            'purchase_date' => 'required|date',
            'asset_name'    => 'required|string|max:255',
            'asset_code'    => 'required|string|unique:assets_management,asset_code,' . $id,
            'status_asset'  => 'required|string',
            'in_use_date'   => 'required|date',
        ]);

        $asset->update($request->all());

        return redirect()->route('asset-management.index')->with('success', 'Data asset berhasil diperbarui.');
    }

    // Halaman Detail Asset & Riwayat
    public function show($id)
    {
        $asset = AssetManagement::with(['userHistories', 'maintenanceHistories'])->findOrFail($id);
        return view('assets_management.show', compact('asset'));
    }

    // Simpan Riwayat Pengguna
    public function storeUserHistory(Request $request, $id)
    {
        $asset = AssetManagement::findOrFail($id);

        $request->validate([
            'usage_date'  => 'required|date',
            'location'    => 'required|string',
            'new_user'    => 'required|string',
            'officer'     => 'required|string',
        ]);

        AssetUserHistory::create([
            'asset_id'    => $asset->id,
            'usage_date'  => $request->usage_date,
            'asset_code'  => $asset->asset_code,
            'location'    => $request->location,
            'old_user'    => $request->old_user,
            'new_user'    => $request->new_user,
            'description' => $request->description,
            'officer'     => $request->officer,
        ]);

        return redirect()->route('asset-management.show', $id)->with('success', 'Riwayat pengguna berhasil ditambahkan.');
    }

    // Update Riwayat Pengguna
    public function updateUserHistory(Request $request, $historyId)
    {
        $history = AssetUserHistory::findOrFail($historyId);

        $request->validate([
            'usage_date'  => 'required|date',
            'location'    => 'required|string',
            'new_user'    => 'required|string',
            'officer'     => 'required|string',
        ]);

        $history->update([
            'usage_date'  => $request->usage_date,
            'location'    => $request->location,
            'old_user'    => $request->old_user,
            'new_user'    => $request->new_user,
            'description' => $request->description,
            'officer'     => $request->officer,
        ]);

        return redirect()->route('asset-management.show', $history->asset_id)->with('success', 'Riwayat pengguna berhasil diperbarui.');
    }

    // Simpan Riwayat Pemeliharaan
    public function storeMaintenanceHistory(Request $request, $id)
    {
        $asset = AssetManagement::findOrFail($id);

        $request->validate([
            'maintenance_date'  => 'required|date',
            'location'          => 'required|string',
            'user_name'         => 'required|string',
            'type'              => 'required|string',
            'component'         => 'required|string',
            'new_specification' => 'required|string',
            'officer'           => 'required|string',
        ]);

        AssetMaintenanceHistory::create([
            'asset_id'          => $asset->id,
            'maintenance_date'  => $request->maintenance_date,
            'asset_code'        => $asset->asset_code,
            'location'          => $request->location,
            'user_name'         => $request->user_name,
            'type'              => $request->type,
            'component'         => $request->component,
            'old_specification' => $request->old_specification,
            'new_specification' => $request->new_specification,
            'description'       => $request->description,
            'officer'           => $request->officer,
        ]);

        return redirect()->route('asset-management.show', $id)->with('success', 'Riwayat pemeliharaan berhasil ditambahkan.');
    }

    // Update Riwayat Pemeliharaan
    public function updateMaintenanceHistory(Request $request, $historyId)
    {
        $history = AssetMaintenanceHistory::findOrFail($historyId);

        $request->validate([
            'maintenance_date'  => 'required|date',
            'location'          => 'required|string',
            'user_name'         => 'required|string',
            'type'              => 'required|string',
            'component'         => 'required|string',
            'new_specification' => 'required|string',
            'officer'           => 'required|string',
        ]);

        $history->update([
            'maintenance_date'  => $request->maintenance_date,
            'location'          => $request->location,
            'user_name'         => $request->user_name,
            'type'              => $request->type,
            'component'         => $request->component,
            'old_specification' => $request->old_specification,
            'new_specification' => $request->new_specification,
            'description'       => $request->description,
            'officer'           => $request->officer,
        ]);

        return redirect()->route('asset-management.show', $history->asset_id)->with('success', 'Riwayat pemeliharaan berhasil diperbarui.');
    }

    // Hapus Asset
    public function destroy($id)
    {
        $asset = AssetManagement::findOrFail($id);
        $asset->delete();

        return redirect()->route('asset-management.index')->with('success', 'Asset berhasil dihapus.');
    }
}
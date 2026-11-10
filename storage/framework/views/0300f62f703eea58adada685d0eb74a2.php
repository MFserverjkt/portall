

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4 py-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Detail Asset: <?php echo e($asset->asset_name); ?></h2>
        <div>
            <a href="#" class="btn btn-outline-dark btn-sm me-2">Ekspor Excel</a>
            <a href="#" class="btn btn-outline-dark btn-sm me-2">Ekspor PDF</a>
            <a href="<?php echo e(route('asset-management.index')); ?>" class="btn btn-secondary btn-sm">KEMBALI</a>
        </div>
    </div>

    <!-- Data Asset Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="small text-muted fw-bold">TANGGAL ASSET BARU</label>
                    <input type="text" class="form-control bg-light" value="<?php echo e($asset->purchase_date); ?>" readonly>
                </div>
                <div class="col-md-3">
                    <label class="small text-muted fw-bold">KONDISI</label>
                    <input type="text" class="form-control bg-light" value="<?php echo e($asset->condition); ?>" readonly>
                </div>
                <div class="col-md-3">
                    <label class="small text-muted fw-bold">STATUS ASSET</label>
                    <input type="text" class="form-control bg-light" value="<?php echo e($asset->status_asset); ?>" readonly>
                </div>
                <div class="col-md-3">
                    <label class="small text-muted fw-bold">TANGGAL TERDAFTAR</label>
                    <input type="text" class="form-control bg-light" value="<?php echo e($asset->created_at->format('Y-m-d')); ?>" readonly>
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
                    <?php $__empty_1 = true; $__currentLoopData = $asset->userHistories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="ps-4"><?php echo e($history->usage_date); ?></td>
                        <td><?php echo e($history->asset_code); ?></td>
                        <td><?php echo e($history->location); ?></td>
                        <td><?php echo e($history->new_user); ?></td>
                        <td><?php echo e($history->description ?? '-'); ?></td>
                        <td><?php echo e($history->officer); ?></td>
                        <td class="text-end pe-4">
                            <span class="badge bg-success rounded-circle p-2"></span>
                            <span class="badge bg-warning rounded-circle p-2"></span>
                            <span class="badge bg-danger rounded-circle p-2"></span>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="7" class="text-center py-3 text-muted">Belum ada riwayat pengguna.</td></tr>
                    <?php endif; ?>
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
                    <?php $__empty_1 = true; $__currentLoopData = $asset->maintenanceHistories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $maint): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="ps-4"><?php echo e($maint->maintenance_date); ?></td>
                        <td><?php echo e($maint->asset_code); ?></td>
                        <td><?php echo e($maint->location); ?></td>
                        <td><?php echo e($maint->user_name); ?></td>
                        <td><?php echo e($maint->description ?? '-'); ?></td>
                        <td><?php echo e($maint->officer); ?></td>
                        <td class="text-end pe-4">
                            <span class="badge bg-success rounded-circle p-2"></span>
                            <span class="badge bg-warning rounded-circle p-2"></span>
                            <span class="badge bg-danger rounded-circle p-2"></span>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="7" class="text-center py-3 text-muted">Belum ada riwayat pemeliharaan.</td></tr>
                    <?php endif; ?>
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
            <form action="<?php echo e(route('asset-management.user-history.store', $asset->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">TANGGAL PEMAKAIAN</label>
                        <input type="date" name="usage_date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">KODE ASSET</label>
                        <input type="text" class="form-control bg-light" value="<?php echo e($asset->asset_code); ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">LOKASI</label>
                        <input type="text" name="location" class="form-control" required>
                    </div>

                    <?php
                        $latestHistory = $asset->userHistories()->latest()->first();
                        $lastUserName = $latestHistory ? $latestHistory->new_user : null;
                    ?>

                    <?php if($lastUserName): ?>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">NAMA PENGGUNA LAMA</label>
                        <input type="text" name="old_user" class="form-control bg-light" value="<?php echo e($lastUserName); ?>" readonly>
                    </div>
                    <?php endif; ?>

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
            <form action="<?php echo e(route('asset-management.maintenance-history.store', $asset->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>
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

                    <?php
                        $activeUser = $asset->userHistories()->latest()->first();
                        $currentUserName = $activeUser ? $activeUser->new_user : '';
                    ?>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">PENGGUNA</label>
                        <input type="text" name="user_name" class="form-control bg-light" value="<?php echo e($currentUserName); ?>" readonly required>
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\Documents\IT\ngrok-v3-stable-windows-386\portal\resources\views/assets_management/show.blade.php ENDPATH**/ ?>
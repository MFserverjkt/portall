

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4 py-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Asset</h2>
        <div>
            <a href="#" class="btn btn-outline-dark btn-sm me-2">Ekspor Excel</a>
            <a href="#" class="btn btn-outline-dark btn-sm me-2">Ekspor PDF</a>
            <a href="<?php echo e(route('asset-management.create')); ?>" class="btn btn-primary btn-sm fw-bold px-3">CREATE ASSET</a>
        </div>
    </div>

    <!-- Filter Box -->
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('asset-management.index')); ?>">
                <input type="text" name="search" value="<?php echo e(request('search')); ?>" class="form-control" placeholder="--- FILTER KODE, LOKASI, PENGGUNA, PETUGAS ---" onchange="this.form.submit()">
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
                    <?php $__empty_1 = true; $__currentLoopData = $assets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $asset): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="ps-4"><?php echo e($asset->purchase_date); ?></td>
                        <td class="fw-bold"><?php echo e($asset->asset_code); ?></td>
                        <td><?php echo e($asset->status_asset); ?></td>
                        <td><?php echo e($asset->asset_name); ?></td>
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="<?php echo e(route('asset-management.show', $asset->id)); ?>" class="btn btn-sm btn-success rounded-circle" title="Detail"><i class="bi bi-eye"></i></a>
                                <a href="#" class="btn btn-sm btn-warning rounded-circle" title="Edit"><i class="bi bi-pencil"></i></a>
                                <form action="<?php echo e(route('asset-management.destroy', $asset->id)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Hapus asset ini?')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-danger rounded-circle" title="Hapus"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">Belum ada data asset.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\Documents\IT\ngrok-v3-stable-windows-386\portal\resources\views/assets_management/index.blade.php ENDPATH**/ ?>
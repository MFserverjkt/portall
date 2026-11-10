

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold m-0"><i class="bi bi-people me-2 text-primary"></i> User Management</h3>
            <p class="text-muted small mb-0">Kelola pengguna dan hak akses berdasarkan lokasi cabang Maison Feerie.</p>
        </div>
        <a href="<?php echo e(route('users.create')); ?>" class="btn btn-primary">
            <i class="bi bi-person-plus me-1"></i> Tambah User Baru
        </a>
    </div>

    <!-- Filter Branch -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="<?php echo e(route('users.index')); ?>" method="GET" class="row g-3 align-items-center">
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Filter Cabang / Outlet</label>
                    <select name="branch" class="form-select" onchange="this.form.submit()">
                        <option value="">-- Semua Cabang --</option>
                        <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($code); ?>" <?php echo e(request('branch') == $code ? 'selected' : ''); ?>>
                                [<?php echo e($code); ?>] <?php echo e($name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <?php if(request('branch')): ?>
                    <div class="col-md-2 align-self-end">
                        <a href="<?php echo e(route('users.index')); ?>" class="btn btn-outline-secondary w-100">Reset Filter</a>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- Tabel User (Dilengkapi Scroll & Sticky Header) -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive" style="max-height: 550px; overflow-y: auto;">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light sticky-top" style="z-index: 1;">
                        <tr>
                            <th>#</th>
                            <th>Nama User</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Cabang / Outlet</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($loop->iteration); ?></td>
                                <td class="fw-semibold"><?php echo e($user->name); ?></td>
                                <td><?php echo e($user->email); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo e($user->role === 'ADMIN' ? 'danger' : ($user->role === 'IT' ? 'info text-dark' : 'secondary')); ?>">
                                        <?php echo e($user->role); ?>

                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        <i class="bi bi-geo-alt-fill text-danger me-1"></i><?php echo e($user->branch_name); ?> (<?php echo e($user->branch_code); ?>)
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="<?php echo e(route('users.edit', $user->id)); ?>" class="btn btn-sm btn-outline-warning me-1">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="<?php echo e(route('users.destroy', $user->id)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Data user tidak ditemukan.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\Documents\IT\ngrok-v3-stable-windows-386\portal\resources\views/users/index.blade.php ENDPATH**/ ?>
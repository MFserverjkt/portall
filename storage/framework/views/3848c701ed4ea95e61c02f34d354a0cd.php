<?php $__env->startSection('content'); ?>
<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold"><i class="bi bi-shield-lock me-2"></i> Pengaturan Hak Akses Role</h3>
    </div>

    <!-- Alert Notifikasi -->
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> <?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Nav Tab / Pilihan Role -->
    <ul class="nav nav-tabs border-bottom-0" id="roleTab" role="tablist">
        <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php $roleSlug = Str::slug($role); ?>
            <li class="nav-item" role="presentation">
                <button class="nav-item nav-link fw-bold px-4 <?php echo e($index === 0 ? 'active' : ''); ?>" 
                        id="tab-<?php echo e($roleSlug); ?>" 
                        data-bs-toggle="tab" 
                        data-bs-target="#content-<?php echo e($roleSlug); ?>" 
                        type="button" 
                        role="tab">
                    <i class="bi bi-person-badge me-1"></i> Role <?php echo e($role); ?>

                </button>
            </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>

    <div class="tab-content bg-white p-4 border rounded-bottom shadow-sm" id="roleTabContent">
        <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php $roleSlug = Str::slug($role); ?>
            <div class="tab-pane fade <?php echo e($index === 0 ? 'show active' : ''); ?>" id="content-<?php echo e($roleSlug); ?>" role="tabpanel">
                
                <form action="<?php echo e(route('users.roles.update')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <!-- Hidden Field Role -->
                    <input type="hidden" name="role" value="<?php echo e($role); ?>">

                    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom flex-wrap gap-2">
                        <h5 class="text-primary fw-bold mb-0">Atur Hak Akses Menu untuk Role: <span class="badge bg-primary"><?php echo e($role); ?></span></h5>
                        
                        <div class="d-flex align-items-center gap-3">
                            <!-- Toggle Check All Global -->
                            <div class="form-check form-switch fs-6 mb-0">
                                <input class="form-check-input select-all-global" 
                                       type="checkbox" 
                                       data-role-slug="<?php echo e($roleSlug); ?>"
                                       id="check_global_<?php echo e($roleSlug); ?>">
                                <label class="form-check-label fw-bold text-secondary" for="check_global_<?php echo e($roleSlug); ?>">Pilih Semua Menu</label>
                            </div>

                            <button type="submit" class="btn btn-success fw-bold">
                                <i class="bi bi-save me-1"></i> Simpan Hak Akses <?php echo e($role); ?>

                            </button>
                        </div>
                    </div>

                    <?php if(isset($permissions) && $permissions->isNotEmpty()): ?>
                        <div class="row">
                            <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category => $permissionList): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $catSlug = Str::slug($category);
                                    $allChecked = $permissionList->every(function($p) use ($rolePermissions, $role) {
                                        return in_array($p->id, $rolePermissions[$role] ?? []);
                                    });
                                ?>
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card h-100 border shadow-sm">
                                        <div class="card-header bg-light d-flex justify-content-between align-items-center fw-bold text-uppercase">
                                            <span><i class="bi bi-folder2-open me-2 text-primary"></i> <?php echo e($category); ?></span>
                                            
                                            <!-- Switch Select All per Kategori -->
                                            <div class="form-check form-switch mb-0 fs-6">
                                                <input class="form-check-input select-all-category" 
                                                       type="checkbox" 
                                                       data-role-slug="<?php echo e($roleSlug); ?>" 
                                                       data-category="<?php echo e($catSlug); ?>"
                                                       id="check_all_<?php echo e($roleSlug); ?>_<?php echo e($catSlug); ?>" 
                                                       title="Pilih Semua di Kategori Ini"
                                                       <?php echo e($allChecked ? 'checked' : ''); ?>>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <?php $__currentLoopData = $permissionList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $perm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php
                                                    $isChecked = in_array($perm->id, $rolePermissions[$role] ?? []);
                                                ?>
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input perm-checkbox perm-<?php echo e($roleSlug); ?> perm-<?php echo e($roleSlug); ?>-<?php echo e($catSlug); ?>" 
                                                           type="checkbox" 
                                                           name="permissions[]" 
                                                           value="<?php echo e($perm->id); ?>" 
                                                           id="perm_<?php echo e($roleSlug); ?>_<?php echo e($perm->id); ?>"
                                                           data-role-slug="<?php echo e($roleSlug); ?>"
                                                           data-category="<?php echo e($catSlug); ?>"
                                                           <?php echo e($isChecked ? 'checked' : ''); ?>>
                                                    <label class="form-check-label cursor-pointer" for="perm_<?php echo e($roleSlug); ?>_<?php echo e($perm->id); ?>">
                                                        <strong><?php echo e($perm->name); ?></strong>
                                                        <?php if(!empty($perm->description)): ?>
                                                            <br><small class="text-muted d-block mt-1"><?php echo e($perm->description); ?></small>
                                                        <?php endif; ?>
                                                    </label>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <!-- FALLBACK JIKA DYNAMIC PERMISSIONS KOSONG -->
                        <div class="row">
                            <?php
                                $fallbackGroups = [
                                    'USER' => [
                                        ['val' => 'users.index', 'title' => 'User Management', 'desc' => 'Mengakses halaman daftar user'],
                                        ['val' => 'users.roles', 'title' => 'User Role & Hak Akses', 'desc' => 'Mengatur role dan hak akses pengguna']
                                    ],
                                    'ASSET' => [
                                        ['val' => 'assets.index', 'title' => 'Inventori Asset', 'desc' => 'Melihat dan mengelola inventori aset']
                                    ],
                                    'TIKET' => [
                                        ['val' => 'tickets.index', 'title' => 'Lihat Daftar Tiket', 'desc' => 'Melihat daftar seluruh tiket perbaikan'],
                                        ['val' => 'tickets.create', 'title' => 'Buat Tiket Baru', 'desc' => 'Membuat tiket pengajuan perbaikan baru'],
                                        ['val' => 'tickets.bast.create', 'title' => 'Proses BAST Tiket', 'desc' => 'Memproses Berita Acara Serah Terima (BAST)']
                                    ],
                                    'REPORT' => [
                                        ['val' => 'report.it', 'title' => 'Report Corrective IT', 'desc' => 'Melihat laporan perbaikan divisi IT'],
                                        ['val' => 'report.maintenance', 'title' => 'Report Corrective Maintenance', 'desc' => 'Melihat laporan perbaikan divisi Maintenance']
                                    ]
                                ];
                            ?>

                            <?php $__currentLoopData = $fallbackGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $groupName => $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php $groupSlug = Str::slug($groupName); ?>
                                <div class="col-md-4 mb-3">
                                    <div class="card h-100 border shadow-sm">
                                        <div class="card-header bg-light d-flex justify-content-between align-items-center fw-bold">
                                            <span><i class="bi bi-folder me-1 text-primary"></i> <?php echo e($groupName); ?></span>
                                            <div class="form-check form-switch mb-0 fs-6">
                                                <input class="form-check-input select-all-category" 
                                                       type="checkbox" 
                                                       data-role-slug="<?php echo e($roleSlug); ?>" 
                                                       data-category="<?php echo e($groupSlug); ?>"
                                                       id="check_all_<?php echo e($roleSlug); ?>_<?php echo e($groupSlug); ?>">
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input perm-checkbox perm-<?php echo e($roleSlug); ?> perm-<?php echo e($roleSlug); ?>-<?php echo e($groupSlug); ?>" 
                                                           type="checkbox" 
                                                           name="permissions[]" 
                                                           value="<?php echo e($item['val']); ?>" 
                                                           id="fb_<?php echo e($roleSlug); ?>_<?php echo e(Str::slug($item['val'])); ?>"
                                                           data-role-slug="<?php echo e($roleSlug); ?>"
                                                           data-category="<?php echo e($groupSlug); ?>"
                                                           <?php echo e(in_array($item['val'], $rolePermissions[$role] ?? []) ? 'checked' : ''); ?>>
                                                    <label class="form-check-label fw-bold cursor-pointer" for="fb_<?php echo e($roleSlug); ?>_<?php echo e(Str::slug($item['val'])); ?>">
                                                        <?php echo e($item['title']); ?>

                                                    </label>
                                                    <small class="d-block text-muted"><?php echo e($item['desc']); ?></small>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>

                    <div class="d-flex justify-content-end mt-3">
                        <button type="submit" class="btn btn-success btn-lg px-4 fw-bold">
                            <i class="bi bi-save me-1"></i> Simpan Hak Akses <?php echo e($role); ?>

                        </button>
                    </div>
                </form>

            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>

<!-- Script JS Interaktif -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // 1. Fitur Checkbox "Select All per Kategori"
        document.querySelectorAll('.select-all-category').forEach(switchEl => {
            switchEl.addEventListener('change', function () {
                const roleSlug = this.getAttribute('data-role-slug');
                const category = this.getAttribute('data-category');
                const checkboxes = document.querySelectorAll(`.perm-${roleSlug}-${category}`);

                checkboxes.forEach(cb => {
                    cb.checked = this.checked;
                });
                
                updateGlobalSwitch(roleSlug);
            });
        });

        // 2. Fitur Checkbox "Select All Global" (Pilih Semua Menu)
        document.querySelectorAll('.select-all-global').forEach(globalSwitch => {
            globalSwitch.addEventListener('change', function () {
                const roleSlug = this.getAttribute('data-role-slug');
                const targetPane = document.getElementById(`content-${roleSlug}`);
                
                if (targetPane) {
                    const allCheckboxes = targetPane.querySelectorAll('.perm-checkbox, .select-all-category');
                    allCheckboxes.forEach(cb => {
                        cb.checked = this.checked;
                    });
                }
            });
        });

        // 3. Auto Check/Uncheck Parent Switches saat Checkbox Item Berubah
        document.querySelectorAll('.perm-checkbox').forEach(cb => {
            cb.addEventListener('change', function() {
                const roleSlug = this.getAttribute('data-role-slug');
                const category = this.getAttribute('data-category');
                
                // Update switch kategori
                const categorySwitch = document.getElementById(`check_all_${roleSlug}_${category}`);
                if (categorySwitch) {
                    const categoryItems = document.querySelectorAll(`.perm-${roleSlug}-${category}`);
                    categorySwitch.checked = Array.from(categoryItems).every(c => c.checked);
                }

                // Update switch global
                updateGlobalSwitch(roleSlug);
            });
        });

        // Helper untuk sinkronisasi Switch Global
        function updateGlobalSwitch(roleSlug) {
            const globalSwitch = document.getElementById(`check_global_${roleSlug}`);
            if (globalSwitch) {
                const allItems = document.querySelectorAll(`.perm-${roleSlug}`);
                globalSwitch.checked = allItems.length > 0 && Array.from(allItems).every(c => c.checked);
            }
        }
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\hans\1\portal\resources\views/users/roles.blade.php ENDPATH**/ ?>
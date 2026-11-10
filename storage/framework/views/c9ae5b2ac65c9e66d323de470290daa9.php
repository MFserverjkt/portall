<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Daftar Tiket Perbaikan (Corrective)</h3>
    <a href="<?php echo e(route('tickets.create')); ?>" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-circle me-1"></i> Buat Tiket Baru
    </a>
</div>

<!-- FILTER BOX (BRANCH & STATUS) -->
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body p-3">
        <form method="GET" action="<?php echo e(url()->current()); ?>" class="row g-2 align-items-center">
            
            <!-- Filter Branch / Cabang -->
            <div class="col-md-4 col-sm-6">
                <label for="branch" class="form-label small fw-bold mb-1">Filter Branch / Cabang</label>
                <select name="branch" id="branch" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">-- Semua Branch --</option>
                    <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($branch); ?>" <?php echo e(request('branch') == $branch ? 'selected' : ''); ?>>
                            [<?php echo e($branch); ?>]
                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <!-- Filter Status (Terbuka, On Check, On Progress, Menunggu Konfirmasi, Completed) -->
            <div class="col-md-4 col-sm-6">
                <label for="status" class="form-label small fw-bold mb-1">Filter Status</label>
                <select name="status" id="status" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">-- Semua Status --</option>
                    <option value="Terbuka" <?php echo e(request('status') == 'Terbuka' ? 'selected' : ''); ?>>Terbuka</option>
                    <option value="On Check" <?php echo e(request('status') == 'On Check' ? 'selected' : ''); ?>>On Check</option>
                    <option value="On Progress" <?php echo e(request('status') == 'On Progress' ? 'selected' : ''); ?>>On Progress</option>
                    <option value="Menunggu Konfirmasi" <?php echo e(request('status') == 'Menunggu Konfirmasi' ? 'selected' : ''); ?>>Menunggu Konfirmasi</option>
                    <option value="Completed" <?php echo e(request('status') == 'Completed' ? 'selected' : ''); ?>>Completed</option>
                </select>
            </div>
            
            <!-- Tombol Reset Filter -->
            <?php if(request('branch') || request('status')): ?>
            <div class="col-auto mt-4">
                <a href="<?php echo e(url()->current()); ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-x-circle me-1"></i> Reset
                </a>
            </div>
            <?php endif; ?>
        </form>
    </div>
</div>

<!-- TABEL DAFTAR TIKET -->
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>No. Tiket</th>
                        <th>Pelapor</th>
                        <th>Divisi Tujuan</th>
                        <th>Nama Aset</th>
                        <th>Keluhan</th>
                        <th>Status</th>
                        <th width="220">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><strong><?php echo e($ticket->ticket_number); ?></strong></td>
                        
                        <!-- Kolom Pelapor & Lokasi Branch Tiket -->
                        <td>
                            <div class="fw-bold">
                                <?php echo e($ticket->reporter_name ?? $ticket->user->name ?? '-'); ?>

                            </div>
                            
                            <?php
                                $branchInfo = $ticket->branch_code 
                                    ?? $ticket->branch_name 
                                    ?? $ticket->user?->branch_code 
                                    ?? $ticket->user?->branch_name;
                            ?>

                            <?php if($branchInfo): ?>
                                <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">
                                    <i class="bi bi-geo-alt me-1"></i>[<?php echo e($branchInfo); ?>]
                                </small>
                            <?php endif; ?>
                        </td>

                        <td>
                            <?php
                                $dept = strtoupper($ticket->department ?? '');
                                $badgeClass = 'bg-warning text-dark';
                                if ($dept === 'MAINTENANCE') {
                                    $badgeClass = 'bg-info text-dark';
                                }
                            ?>
                            <span class="badge <?php echo e($badgeClass); ?> text-uppercase">
                                <?php echo e($ticket->department); ?>

                            </span>
                        </td>
                        <td><?php echo e($ticket->asset->asset_name ?? $ticket->custom_asset_name ?? '-'); ?></td>
                        <td><?php echo e($ticket->title); ?></td>
                        <td>
                            <?php
                                // Menentukan status visual dan badge
                                $workStatus = $ticket->work_status ?? $ticket->status;
                                $statusBadge = 'secondary';

                                if (in_array($workStatus, ['Terbuka', 'On Check', 'Pengajuan Sparepart', 'On Progress', 'Diproses'])) {
                                    $statusBadge = 'warning text-dark';
                                } elseif ($workStatus === 'Menunggu Konfirmasi') {
                                    $statusBadge = 'info text-dark';
                                } elseif (in_array($workStatus, ['Completed', 'Selesai', 'Selesai (DONE)'])) {
                                    $statusBadge = 'success';
                                }
                            ?>
                            <span class="badge bg-<?php echo e($statusBadge); ?>">
                                <?php echo e(in_array($workStatus, ['On Check', 'Pengajuan Sparepart']) ? 'On Progress (' . $workStatus . ')' : $workStatus); ?>

                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-1 flex-wrap">
                                <!-- Tombol View Detail -->
                                <a href="<?php echo e(route('tickets.show', $ticket->id)); ?>" class="btn btn-info btn-sm text-white" title="Lihat Detail">
                                    <i class="bi bi-eye"></i> View
                                </a>

                                <!-- Tombol WORK (Ditampilkan untuk role ADMIN, IT, dan MAINTENANCE) -->
                                <?php if(in_array(auth()->user()->role, ['ADMIN', 'IT', 'MAINTENANCE']) && !in_array($ticket->status, ['Selesai', 'Selesai (DONE)'])): ?>
                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#modalWork<?php echo e($ticket->id); ?>" title="Update Pengerjaan Tiket">
                                        <i class="bi bi-tools"></i> WORK
                                    </button>

                                    <!-- MODAL POPUP WORK -->
                                    <div class="modal fade" id="modalWork<?php echo e($ticket->id); ?>" tabindex="-1" aria-labelledby="modalWorkLabel<?php echo e($ticket->id); ?>" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content text-start">
                                                <form action="<?php echo e(route('tickets.work.update', $ticket->id)); ?>" method="POST">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('PATCH'); ?>
                                                    <div class="modal-header bg-dark text-white">
                                                        <h5 class="modal-title fs-6 fw-bold" id="modalWorkLabel<?php echo e($ticket->id); ?>">
                                                            <i class="bi bi-tools me-2"></i>Form Work Progress - <?php echo e($ticket->ticket_number); ?>

                                                        </h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <!-- Input Petugas -->
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold text-dark">Petugas / Teknisi <span class="text-danger">*</span></label>
                                                            <input type="text" name="technician_name" class="form-control" value="<?php echo e($ticket->technician_name ?? auth()->user()->name); ?>" required placeholder="Nama teknisi penanggung jawab">
                                                        </div>

                                                        <!-- Input Tindakan Pengerjaan -->
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold text-dark">Tindakan Pengerjaan <span class="text-danger">*</span></label>
                                                            <textarea name="action_taken" class="form-control" rows="3" required placeholder="Jelaskan analisa / tindakan perbaikan yang dilakukan"><?php echo e($ticket->action_taken); ?></textarea>
                                                        </div>

                                                        <!-- Input Tanggal Target Selesai -->
                                                        <div class="mb-3" id="target-wrapper-<?php echo e($ticket->id); ?>">
                                                            <label class="form-label fw-bold text-dark">Tanggal Target Selesai <span class="text-danger">*</span></label>
                                                            <input 
                                                                type="date" 
                                                                name="target_completion_date" 
                                                                id="target-date-<?php echo e($ticket->id); ?>" 
                                                                class="form-control" 
                                                                min="<?php echo e(date('Y-m-d')); ?>"
                                                                value="<?php echo e($ticket->target_completion_date); ?>"
                                                            >
                                                        </div>

                                                        <!-- Select Status Pengerjaan -->
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold text-dark">Status Pengerjaan <span class="text-danger">*</span></label>
                                                            <select name="work_status" class="form-select status-pengerjaan-select" data-ticket-id="<?php echo e($ticket->id); ?>" required>
                                                                <option value="On Check" <?php echo e(($ticket->work_status ?? '') === 'On Check' ? 'selected' : ''); ?>>On Check</option>
                                                                <option value="Pengajuan Sparepart" <?php echo e(($ticket->work_status ?? '') === 'Pengajuan Sparepart' ? 'selected' : ''); ?>>Pengajuan Sparepart</option>
                                                                <option value="Completed" <?php echo e(($ticket->work_status ?? '') === 'Completed' ? 'selected' : ''); ?>>Completed</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary btn-sm fw-bold">
                                                            <i class="bi bi-save me-1"></i> Simpan Work
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <!-- Tombol BAST -->
                                <?php if(!$ticket->bast && in_array(auth()->user()->role, ['ADMIN', 'IT', 'MAINTENANCE']) && in_array($ticket->work_status, ['Completed', 'On Check', 'Pengajuan Sparepart']) && !in_array($ticket->status, ['Selesai', 'Selesai (DONE)'])): ?>
                                    <a href="<?php echo e(route('tickets.bast.create', $ticket->id)); ?>" class="btn btn-primary btn-sm" title="Buat BAST">
                                        <i class="bi bi-file-earmark-text"></i> BAST
                                    </a>
                                <?php endif; ?>

                                <!-- Tombol DONE -->
                                <?php if($ticket->status === 'Menunggu Konfirmasi' && ($ticket->user_id === auth()->id() || auth()->user()->role === 'ADMIN') && !in_array(auth()->user()->role, ['IT', 'MAINTENANCE'])): ?>
                                    <button type="button" class="btn btn-success btn-sm fw-bold" data-bs-toggle="modal" data-bs-target="#modalDone<?php echo e($ticket->id); ?>" title="Konfirmasi Selesai">
                                        <i class="bi bi-check-circle me-1"></i> VALIDASI BAST
                                    </button>

                                    <!-- MODAL POPUP INPUT TANGGAL SELESAI -->
                                    <div class="modal fade" id="modalDone<?php echo e($ticket->id); ?>" tabindex="-1" aria-labelledby="modalDoneLabel<?php echo e($ticket->id); ?>" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content text-start">
                                                <form action="<?php echo e(route('tickets.markAsDone', $ticket->id)); ?>" method="POST">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('PATCH'); ?>
                                                    <div class="modal-header bg-success text-white">
                                                        <h5 class="modal-title fs-6 fw-bold" id="modalDoneLabel<?php echo e($ticket->id); ?>">
                                                            <i class="bi bi-calendar-check me-2"></i>Konfirmasi Tiket Selesai
                                                        </h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p class="mb-3 text-dark">
                                                            Silakan pilih tanggal & waktu penyelesaian untuk tiket <strong><?php echo e($ticket->ticket_number); ?></strong>:
                                                        </p>
                                                        
                                                        <div class="mb-3">
                                                            <label for="completed_at_<?php echo e($ticket->id); ?>" class="form-label fw-bold text-dark">Tanggal & Waktu Selesai <span class="text-danger">*</span></label>
                                                            <input 
                                                                type="datetime-local" 
                                                                name="completed_at" 
                                                                id="completed_at_<?php echo e($ticket->id); ?>"
                                                                class="form-control" 
                                                                value="<?php echo e(now()->format('Y-m-d\TH:i')); ?>" 
                                                                min="<?php echo e(now()->format('Y-m-d\TH:i')); ?>"
                                                                required
                                                            >
                                                            <small class="text-muted">Tanggal tidak boleh memilih tanggal/waktu yang sudah berlalu.</small>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-success btn-sm fw-bold">
                                                            <i class="bi bi-check-lg me-1"></i> Simpan & Selesaikan
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                
                                <?php if(in_array(auth()->user()->role, ['ADMIN', 'IT']) || auth()->user()->hasRole('ADMIN')): ?>
                                    <form action="<?php echo e(route('tickets.destroy', $ticket->id)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tiket #<?php echo e($ticket->ticket_number); ?> ini?');">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-danger btn-sm mb-1">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </form>
                                <?php endif; ?>
    
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted py-3">
                            <i class="bi bi-inbox d-block fs-3 mb-1"></i>
                            Tidak ada data tiket terdaftar.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const statusSelects = document.querySelectorAll('.status-pengerjaan-select');

    function toggleTargetDate(selectElement) {
        const ticketId = selectElement.getAttribute('data-ticket-id');
        const wrapper = document.getElementById(`target-wrapper-${ticketId}`);
        const input = document.getElementById(`target-date-${ticketId}`);

        if (selectElement.value === 'Completed') {
            wrapper.style.display = 'none';
            input.removeAttribute('required');
            input.value = ''; 
        } else {
            wrapper.style.display = 'block';
            input.setAttribute('required', 'required');
        }
    }

    statusSelects.forEach(function (selectElement) {
        toggleTargetDate(selectElement);
        selectElement.addEventListener('change', function () {
            toggleTargetDate(this);
        });
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\hans\1\portal\resources\views/tickets/index.blade.php ENDPATH**/ ?>
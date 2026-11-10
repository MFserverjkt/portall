<?php $__env->startSection('content'); ?>
<div class="container-fluid px-0">

    
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
            <i class="bi bi-check-circle me-1"></i> <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center py-2">
            <h5 class="mb-0 fw-bold fs-6">Form Tiket: <?php echo e($ticket->ticket_number); ?></h5>
            <span class="badge bg-info text-uppercase px-2 py-1"><?php echo e($ticket->department); ?></span>
        </div>
        <div class="card-body">
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <p class="mb-2"><strong>Pelapor:</strong> <?php echo e($ticket->reporter_name ?? $ticket->user->name ?? '-'); ?> <span class="badge bg-secondary text-uppercase ms-1"><?php echo e($ticket->user->role ?? 'USER'); ?></span></p>
                    <p class="mb-0">
                        <strong>Aset Terkait:</strong> 
                        <?php echo e($ticket->asset->asset_name ?? $ticket->custom_asset_name ?? '-'); ?> 
                        <?php if(isset($ticket->asset->asset_code)): ?>
                            (<?php echo e($ticket->asset->asset_code); ?>)
                        <?php endif; ?>
                    </p>
                </div>
                <div class="col-md-6 text-md-start">
                    <p class="mb-2"><strong>Prioritas:</strong> <?php echo e($ticket->priority ?? 'Rendah'); ?></p>
                    <p class="mb-2">
                        <strong>Status Tiket:</strong> 
                        <?php
                            $statusBadge = 'danger';
                            if (in_array($ticket->status, ['Diproses', 'On Progress'])) {
                                $statusBadge = 'warning text-dark';
                            } elseif ($ticket->status === 'Menunggu Konfirmasi') {
                                $statusBadge = 'info text-dark';
                            } elseif (in_array($ticket->status, ['Selesai', 'Completed'])) {
                                $statusBadge = 'success';
                            }
                        ?>
                        <span class="badge bg-<?php echo e($statusBadge); ?>">
                            <i class="bi bi-info-circle me-1"></i><?php echo e($ticket->status); ?>

                        </span>
                    </p>
                    <p class="mb-0">
                        <strong>Cabang / Outlet:</strong> 
                        <span class="badge bg-light text-dark border ms-1">
                            <i class="bi bi-geo-alt text-danger me-1"></i><?php echo e($ticket->branch->name ?? $ticket->branch_name ?? $ticket->branch_code ?? '-'); ?>

                        </span>
                    </p>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold mb-1">Judul Keluhan:</label>
                <input type="text" class="form-control bg-light" value="<?php echo e($ticket->title); ?>" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold mb-1">Deskripsi Kerusakan:</label>
                <textarea class="form-control bg-light" rows="3" readonly><?php echo e($ticket->description); ?></textarea>
            </div>

            
            <div class="mb-2">
                <label class="form-label fw-bold mb-1">Lampiran / Foto Kerusakan:</label>
                <div>
                    <?php
                        $ticketPhoto = $ticket->attachment ?? $ticket->image ?? $ticket->photo ?? $ticket->file ?? null;
                        $photoUrl = null;

                        if ($ticketPhoto) {
                            if (Str::startsWith($ticketPhoto, 'http')) {
                                $photoUrl = $ticketPhoto;
                            } else {
                                $photoUrl = route('view.attachment', ['path' => ltrim($ticketPhoto, '/')]);
                            }
                        }
                    ?>

                    <?php if($ticketPhoto): ?>
                        <div class="d-inline-block position-relative">
                            <a href="<?php echo e($photoUrl); ?>" target="_blank">
                                <img src="<?php echo e($photoUrl); ?>" alt="Foto Kerusakan" class="img-thumbnail rounded shadow-sm" style="max-height: 250px; object-fit: cover;">
                            </a>
                            <div class="mt-1 small text-muted">
                                <span class="text-secondary" style="font-size: 0.75rem;">Path DB: <?php echo e($ticketPhoto); ?></span><br>
                                <i class="bi bi-zoom-in me-1"></i>Klik gambar untuk memperbesar
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="p-3 bg-light text-muted border rounded">
                            <i class="bi bi-image me-1"></i> Tidak ada lampiran foto yang diunggah saat pembuatan tiket.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    
    <?php
        $technicianName = $ticket->technician_name ?? $ticket->bast->technician_name ?? $ticket->bast->user->name ?? null;
        $actionTaken    = $ticket->action_taken ?? $ticket->bast->action_taken ?? $ticket->bast->notes ?? null;
        $targetDate     = $ticket->target_completion_date ?? ($ticket->bast ? $ticket->bast->created_at : null);
        $workStatus     = $ticket->work_status ?? ($ticket->bast ? 'Completed' : $ticket->status);
    ?>

    
    <?php if(!empty($technicianName) || !empty($actionTaken) || !empty($ticket->work_status) || $ticket->bast): ?>
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-2">
            <h6 class="mb-0 fw-bold"><i class="bi bi-tools me-2"></i>Informasi Pengerjaan Teknisi (WORK)</h6>
            <span class="badge bg-light text-primary fw-bold text-uppercase">Progress Perbaikan</span>
        </div>
        <div class="card-body bg-light">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-muted mb-1">Petugas / Teknisi Penanggung Jawab:</label>
                    <div class="fw-bold text-dark fs-6"><?php echo e($technicianName ?? '-'); ?></div>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-muted mb-1">Tanggal Target Selesai / Pengerjaan:</label>
                    <div class="fw-bold text-dark fs-6">
                        <i class="bi bi-calendar-event me-1 text-primary"></i>
                        <?php echo e($targetDate ? \Carbon\Carbon::parse($targetDate)->format('d F Y') : '-'); ?>

                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-muted mb-1">Status Pengerjaan (WORK):</label>
                    <div>
                        <span class="badge bg-<?php echo e(in_array($workStatus, ['Completed', 'Selesai']) ? 'success' : 'warning text-dark'); ?> fs-6">
                            <?php echo e($workStatus ?? 'On Check'); ?>

                        </span>
                    </div>
                </div>
                <div class="col-12 mt-2">
                    <label class="form-label small fw-bold text-muted mb-1">Tindakan Pengerjaan / Analisa Perbaikan:</label>
                    <div class="p-3 bg-white rounded border text-dark" style="min-height: 70px;">
                        <?php echo nl2br(e($actionTaken ?? 'Belum ada catatan tindakan.')); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    
    <?php if($ticket->bast): ?>
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center py-2">
            <h6 class="mb-0 fw-bold"><i class="bi bi-file-earmark-text me-2"></i>Form Berita Acara Serah Terima (BAST) - [Tersimpan]</h6>
            <span class="badge bg-success text-uppercase">Terverifikasi</span>
        </div>
        <div class="card-body bg-light">
            <div class="row g-3">
                <div class="col-md-6">
                    <p class="mb-2"><strong>No. BAST:</strong> <?php echo e($ticket->bast->bast_number ?? 'BAST-'.$ticket->ticket_number); ?></p>
                    <p class="mb-2">
                        <strong>Tanggal Serah Terima:</strong> 
                        <?php echo e(\Carbon\Carbon::parse($ticket->bast->created_at)->format('d F Y')); ?>

                    </p>
                    <p class="mb-2"><strong>Diserahkan Oleh (Teknisi):</strong> <?php echo e($ticket->bast->technician_name ?? $technicianName ?? '-'); ?></p>
                    <p class="mb-0"><strong>Diterima Oleh (Outlet/Cabang):</strong> <?php echo e($ticket->bast->recipient_name ?? $ticket->reporter_name ?? '-'); ?></p>
                </div>
                <div class="col-md-6">
                    <p class="mb-2">
                        <strong>Pekerjaan Selesai:</strong> 
                        <span class="text-success fw-bold"><i class="bi bi-check-lg"></i> Sesuai Keluhan</span>
                    </p>
                    <p class="mb-2">
                        <strong>Kondisi Setelah Perbaikan:</strong> 
                        <?php echo e($ticket->asset->asset_name ?? 'Aset'); ?> <strong>Baik/Normal</strong>
                    </p>
                    <p class="mb-0">
                        <strong>Catatan Tambahan:</strong> 
                        <?php echo e($ticket->bast->notes ?? 'Semua fungsi berjalan normal.'); ?>

                    </p>
                </div>

                
                <div class="col-12 mt-3 pt-3 border-top">
                    <div class="row g-3">
                        
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted mb-2">Lampiran Foto / Bukti Perbaikan:</label>
                            <?php
                                $bastPhoto = $ticket->bast->documentation ?? $ticket->bast->attachment ?? $ticket->bast->photo ?? $ticket->bast->image ?? null;
                                $bastUrl = null;

                                if ($bastPhoto) {
                                    if (Str::startsWith($bastPhoto, 'http')) {
                                        $bastUrl = $bastPhoto;
                                    } else {
                                        $bastUrl = route('view.attachment', ['path' => ltrim($bastPhoto, '/')]);
                                    }
                                }
                            ?>
                            <div>
                                <?php if($bastPhoto): ?>
                                    <div class="d-inline-block">
                                        <a href="<?php echo e($bastUrl); ?>" target="_blank">
                                            <img src="<?php echo e($bastUrl); ?>" alt="Foto BAST" class="img-thumbnail rounded shadow-sm" style="max-height: 180px; object-fit: cover;">
                                        </a>
                                        <div class="mt-1 small text-muted"><i class="bi bi-zoom-in me-1"></i>Klik gambar untuk memperbesar</div>
                                    </div>
                                <?php else: ?>
                                    <div class="p-3 bg-white text-muted border rounded small">
                                        <i class="bi bi-image me-1"></i> Tidak ada lampiran foto pada BAST.
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <?php else: ?>
        
        <div class="alert alert-warning border-warning d-flex align-items-center mb-4" role="alert">
            <i class="bi bi-exclamation-circle fs-5 me-2"></i>
            <div>
                Form BAST belum diisi oleh teknisi. Status tiket masih dalam antrean/pengerjaan.
            </div>
        </div>
    <?php endif; ?>

    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="<?php echo e(route('tickets.index')); ?>" class="btn btn-secondary px-4">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>

        <?php if(!$ticket->bast && in_array(auth()->user()->role, ['ADMIN', 'IT', 'MAINTENANCE']) && !in_array($ticket->status, ['Selesai', 'Selesai (DONE)'])): ?>
            <a href="<?php echo e(route('tickets.bast.create', $ticket->id)); ?>" class="btn btn-warning fw-bold px-4">
                <i class="bi bi-pencil-square me-1"></i> Isi Form BAST
            </a>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\Documents\IT\ngrok-v3-stable-windows-386\portal\resources\views/tickets/show.blade.php ENDPATH**/ ?>
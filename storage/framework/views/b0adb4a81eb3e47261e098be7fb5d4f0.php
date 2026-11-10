<?php $__env->startSection('content'); ?>
<div class="container py-3">
    <div class="card border-0 shadow-sm col-md-9 mx-auto">
        <div class="card-header bg-success text-white py-3">
            <h5 class="mb-0 fw-bold"><i class="bi bi-pencil-square me-2"></i>Form Berita Acara Serah Terima (BAST)</h5>
        </div>
        <div class="card-body p-4">
            <?php if($errors->any()): ?>
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <strong><i class="bi bi-exclamation-triangle-fill me-2"></i> Gagal menyimpan BAST:</strong>
                    <ul class="mb-0 mt-1">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <form action="<?php echo e(route('tickets.storeBast', $ticket->id)); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>

                <!-- 1. Nama Petugas yang Mengerjakan -->
                <div class="mb-3">
                    <label for="technician_name" class="form-label fw-bold">
                        Nama Petugas yang Mengerjakan <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           name="technician_name" 
                           id="technician_name" 
                           class="form-control <?php $__errorArgs = ['technician_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           placeholder="Masukkan nama petugas / teknisi..." 
                           value="<?php echo e(old('technician_name', $ticket->bast->technician_name ?? auth()->user()->name ?? '')); ?>" 
                           required>
                    <?php $__errorArgs = ['technician_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- 2. Tindakan Perbaikan -->
                <div class="mb-3">
                    <label for="action_taken" class="form-label fw-bold">
                        Tindakan Perbaikan <span class="text-danger">*</span>
                    </label>
                    <textarea name="action_taken" 
                              id="action_taken" 
                              class="form-control <?php $__errorArgs = ['action_taken'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                              rows="3" 
                              placeholder="Jelaskan tindakan perbaikan yang dilakukan..." 
                              required><?php echo e(old('action_taken', $ticket->bast->action_taken ?? '')); ?></textarea>
                    <?php $__errorArgs = ['action_taken'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- 3. Penggantian Sparepart / Suku Cadang -->
                <div class="mb-3">
                    <label for="parts_replaced" class="form-label fw-bold">
                        Penggantian Sparepart / Suku Cadang <span class="text-danger">*</span>
                    </label>
                    <textarea name="parts_replaced" 
                              id="parts_replaced" 
                              class="form-control <?php $__errorArgs = ['parts_replaced'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                              rows="2" 
                              placeholder="Sebutkan sparepart yang diganti (Isi '-' atau 'Tidak ada' jika tidak ada penggantian)..." 
                              required><?php echo e(old('parts_replaced', $ticket->bast->parts_replaced ?? '')); ?></textarea>
                    <?php $__errorArgs = ['parts_replaced'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- 4. Unggah Bukti Pekerjaan / Lampiran BAST -->
                <div class="mb-4">
                    <label for="attachment" class="form-label fw-bold">
                        Unggah Bukti Pekerjaan / Lampiran BAST (Foto/PDF) <span class="text-danger">*</span>
                    </label>
                    <input type="file" 
                           name="attachment" 
                           id="attachment" 
                           class="form-control <?php $__errorArgs = ['attachment'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           accept="image/*,.pdf"
                           <?php echo e(isset($ticket->bast->attachment) ? '' : 'required'); ?>>
                    
                    <small class="text-muted d-block mt-1">
                        Format yang didukung: JPG, JPEG, PNG, PDF (Maksimal 5MB)
                    </small>

                    <?php if(isset($ticket->bast->attachment)): ?>
                        <div class="mt-2 p-2 bg-light border rounded d-flex align-items-center justify-content-between">
                            <span class="small text-success fw-semibold">
                                <i class="bi bi-file-check-fill me-1"></i> File lampiran saat ini sudah terunggah.
                            </span>
                            <a href="<?php echo e(asset('storage/' . $ticket->bast->attachment)); ?>" target="_blank" class="btn btn-sm btn-outline-success">
                                <i class="bi bi-eye me-1"></i> Lihat File
                            </a>
                        </div>
                    <?php endif; ?>

                    <?php $__errorArgs = ['attachment'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Tombol Aksi -->
                <div class="d-flex justify-content-between pt-3 border-top">
                    <a href="<?php echo e(route('tickets.show', $ticket->id)); ?>" class="btn btn-secondary px-4">
                        <i class="bi bi-arrow-left me-1"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-success fw-bold px-4">
                        <i class="bi bi-check-circle me-1"></i> Simpan BAST
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\Documents\IT\ngrok-v3-stable-windows-386\portal\resources\views/tickets/bast/create.blade.php ENDPATH**/ ?>
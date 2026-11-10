<?php $__env->startSection('content'); ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<div class="card border-0 shadow-sm col-md-8 mx-auto mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="bi bi-ticket-perforated me-2"></i>Form Tiket Perbaikan Baru</h5>
    </div>
    <div class="card-body">
        <?php if($errors->any()): ?>
            <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                <strong><i class="bi bi-exclamation-triangle-fill me-2"></i> Gagal mengirim tiket:</strong>
                <ul class="mb-0 mt-1">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form action="<?php echo e(route('tickets.store')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>

            <!-- Pelapor Tiket -->
            <div class="mb-3">
                <label for="reporter_name" class="form-label fw-bold">Pelapor Tiket / Nama Staf <span class="text-danger">*</span></label>
                <input 
                    type="text" 
                    name="reporter_name" 
                    id="reporter_name" 
                    class="form-control <?php $__errorArgs = ['reporter_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                    placeholder="Masukkan nama pelapor / staf..." 
                    value="<?php echo e(old('reporter_name')); ?>" 
                    required
                    autocomplete="off"
                >
                <small class="text-muted d-block mt-1">Isi nama staf/personil yang melaporkan kendala ini.</small>
                <?php $__errorArgs = ['reporter_name'];
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

            <!-- Divisi Penanggung Jawab -->
            <div class="mb-3">
                <label class="form-label fw-bold">Divisi Penanggung Jawab (Tujuan Tiket) <span class="text-danger">*</span></label>
                <select name="department" class="form-select <?php $__errorArgs = ['department'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                    <option value="" disabled <?php echo e(old('department') ? '' : 'selected'); ?>>-- Pilih Divisi --</option>
                    <option value="IT" <?php echo e(old('department') == 'IT' ? 'selected' : ''); ?>>IT</option>
                    <option value="MAINTENANCE" <?php echo e(old('department') == 'MAINTENANCE' ? 'selected' : ''); ?>>MAINTENANCE</option>
                </select>
                <?php $__errorArgs = ['department'];
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

            <!-- Cabang / Outlet -->
            <div class="mb-3">
                <label class="form-label fw-bold">Cabang / Outlet <span class="text-danger">*</span></label>
                <?php if(auth()->user()->role === 'OUTLET'): ?>
                    <input type="text" class="form-control bg-light" value="[<?php echo e(auth()->user()->branch_code); ?>] <?php echo e(auth()->user()->branch_name ?? 'Cabang Outlet'); ?>" readonly>
                    <input type="hidden" name="branch_code" id="branch-select" value="<?php echo e(auth()->user()->branch_code); ?>">
                <?php else: ?>
                    <select name="branch_code" id="branch-select" class="form-select <?php $__errorArgs = ['branch_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                        <option value="">-- Pilih Cabang / Outlet --</option>
                        <?php if(isset($branches) && count($branches) > 0): ?>
                            <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code =>$name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($code); ?>" <?php echo e(old('branch_code', auth()->user()->branch_code ?? '') == $code ? 'selected' : ''); ?>>
                                    [<?php echo e($code); ?>] <?php echo e($name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </select>
                <?php endif; ?>
                <?php $__errorArgs = ['branch_code'];
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

            <!-- Pilih Aset Kerusakan (Dropdown / Scan Barcode / Input Manual) -->
            <div class="mb-3">
                <label class="form-label fw-bold">Pilih Aset Kerusakan <span class="text-danger">*</span></label>
                <div class="input-group">
                    <div class="flex-grow-1">
                        <select name="asset_id" id="asset-select" class="form-select <?php $__errorArgs = ['asset_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <option value="">-- Ketik / Cari Aset dari Daftar --</option>
                            <?php if(isset($assets) && count($assets) > 0): ?>
                                <?php $__currentLoopData = $assets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $asset): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($asset->id); ?>" data-code="<?php echo e(trim($asset->asset_code)); ?>" <?php echo e(old('asset_id') == $asset->id ? 'selected' : ''); ?>>
                                        <?php echo e(trim($asset->asset_code)); ?> - <?php echo e($asset->asset_name); ?> <?php echo e($asset->brand ? '('.$asset->brand.')' : ''); ?> [<?php echo e($asset->branch_name ?? $asset->branch_code ?? 'Semua Cabang'); ?>]
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <button type="button" class="btn btn-primary fw-bold px-3" id="btn-scan-barcode" data-bs-toggle="modal" data-bs-target="#barcodeModal">
                        📷 SCAN BARCODE
                    </button>
                </div>
                
                <!-- Input Teks Manual Jika Aset Tidak Terdaftar -->
                <div class="mt-2">
                    <label for="custom_asset_name" class="form-label small text-muted mb-1">Atau Ketik Nama Aset Manual (Jika tidak ada di daftar):</label>
                    <input 
                        type="text" 
                        name="custom_asset_name" 
                        id="custom_asset_name" 
                        class="form-control form-control-sm <?php $__errorArgs = ['custom_asset_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                        placeholder="Contoh: Mesin Kasir / Printer Kasir Manual"
                        value="<?php echo e(old('custom_asset_name')); ?>"
                    >
                </div>

                <?php $__errorArgs = ['asset_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <?php $__errorArgs = ['custom_asset_name'];
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

            <!-- Judul Keluhan -->
            <div class="mb-3">
                <label class="form-label fw-bold">Judul Keluhan / Kerusakan <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Contoh: AC Mati Total / PC Hank" value="<?php echo e(old('title')); ?>" required>
                <?php $__errorArgs = ['title'];
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

            <!-- Prioritas -->
            <div class="mb-3">
                <label class="form-label fw-bold">Prioritas <span class="text-danger">*</span></label>
                <select name="priority" class="form-select <?php $__errorArgs = ['priority'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                    <option value="Rendah" <?php echo e(old('priority', 'Rendah') == 'Rendah' ? 'selected' : ''); ?>>Rendah</option>
                    <option value="Sedang" <?php echo e(old('priority') == 'Sedang' ? 'selected' : ''); ?>>Sedang</option>
                    <option value="Tinggi" <?php echo e(old('priority') == 'Tinggi' ? 'selected' : ''); ?>>Tinggi</option>
                </select>
                <?php $__errorArgs = ['priority'];
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

            <!-- Deskripsi Masalah -->
            <div class="mb-3">
                <label class="form-label fw-bold">Deskripsi Masalah <span class="text-danger">*</span></label>
                <textarea name="description" class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="4" placeholder="Jelaskan detail kendala/kerusakan aset..." required><?php echo e(old('description')); ?></textarea>
                <?php $__errorArgs = ['description'];
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

            <!-- Foto / Lampiran -->
            <div class="mb-3">
                <label class="form-label fw-bold">Foto / Lampiran Kerusakan <span class="text-muted fw-normal">(Opsional)</span></label>
                <input type="file" name="attachment" class="form-control <?php $__errorArgs = ['attachment'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" accept="image/*,.pdf">
                <small class="text-muted d-block mt-1">Format yang diperbolehkan: JPG, PNG, PDF. Maksimal size 5MB.</small>
                <?php $__errorArgs = ['attachment'];
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

            <div class="d-flex justify-content-between mt-4">
                <a href="<?php echo e(route('tickets.index')); ?>" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary px-4 fw-bold">
                    <i class="bi bi-send me-1"></i> Kirim Tiket
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Scanner Barcode -->
<div class="modal fade" id="barcodeModal" tabindex="-1" aria-labelledby="barcodeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="barcodeModalLabel"><i class="bi bi-camera me-2"></i>Scan Barcode / QR Code Aset</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <p class="text-muted small">Arahkan kamera ke label Barcode/QR Code yang ada pada fisik Aset.</p>
                <div id="reader" style="width: 100%; min-height: 250px; background-color: #f8f9fa; border-radius: 8px; overflow: hidden;"></div>
                <div id="scan-result" class="mt-2 text-primary fw-bold"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup Scanner</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://unpkg.com/html5-qrcode"></script>

<script>
    let html5QrCode = null;

    $(document).ready(function() {
        // Inisialisasi Select2 Aset
        $('#asset-select').select2({
            placeholder: "-- Ketik untuk mencari aset --",
            allowClear: true,
            width: '100%'
        });

        // Kosongkan input manual jika dropdown dipilih, dan sebaliknya
        $('#asset-select').on('change', function() {
            if ($(this).val()) {
                $('#custom_asset_name').val('');
            }
        });

        $('#custom_asset_name').on('input', function() {
            if ($(this).val().trim() !== '') {
                $('#asset-select').val(null).trigger('change');
            }
        });

        // Inisialisasi Select2 Cabang jika berupa element <select>
        if ($('#branch-select').is('select')) {
            $('#branch-select').select2({
                placeholder: "-- Pilih Cabang / Outlet --",
                allowClear: true,
                width: '100%'
            });

            // Filter aset otomatis secara Ajax saat cabang diganti
            $('#branch-select').on('change', function() {
                let branchCode = $(this).val();
                if (!branchCode) return;

                $.ajax({
                    url: "<?php echo e(route('tickets.create')); ?>",
                    type: "GET",
                    data: { branch_code: branchCode },
                    dataType: "html",
                    success: function(response) {
                        let newAssets = $(response).find('#asset-select').html();
                        if (newAssets) {
                            $('#asset-select').html(newAssets).trigger('change');
                        }
                    }
                });
            });
        }

        // Buka Modal Scanner
        $('#barcodeModal').on('shown.bs.modal', function () {
            $('#scan-result').text('');
            html5QrCode = new Html5Qrcode("reader");

            const config = { fps: 10, qrbox: { width: 250, height: 150 } };

            html5QrCode.start(
                { facingMode: "environment" },
                config,
                onScanSuccess
            ).catch(err => {
                $('#scan-result').html('<span class="text-danger">Kamera tidak dapat diakses/ditemukan.</span>');
            });
        });

        // Tutup Scanner
        $('#barcodeModal').on('hidden.bs.modal', function () {
            if (html5QrCode) {
                html5QrCode.stop().then(() => {
                    html5QrCode.clear();
                }).catch(err => console.error(err));
            }
        });

        // Callback sukses scan
        function onScanSuccess(decodedText, decodedResult) {
            let scannedCode = decodedText.trim();
            $('#scan-result').text('Terdeteksi: ' + scannedCode);

            // 1. Coba cocokkan terlebih dahulu pada dropdown Select2 yang sedang dimuat
            let matchedValue = null;
            $('#asset-select option').each(function() {
                let optionCode = $(this).data('code') ? $(this).data('code').toString().trim() : '';
                let optionText = $(this).text().trim();

                if (optionCode === scannedCode || optionText.toLowerCase().includes(scannedCode.toLowerCase())) {
                    matchedValue = $(this).val();
                    return false;
                }
            });

            if (matchedValue) {
                applyMatchedAsset(matchedValue);
                return;
            }

            // 2. Jika tidak cocok di Select2 lokal, panggil API /assets/scan-check di server
            fetch("<?php echo e(route('assets.scan-check')); ?>", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>"
                },
                body: JSON.stringify({ asset_code: scannedCode })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success' && data.data) {
                    let asset = data.data;

                    // Buat option baru secara dinamis jika belum terdaftar di dropdown
                    let newOption = new Option(
                        `${asset.asset_code} - ${asset.asset_name} [${asset.branch_name || asset.branch_code || 'Semua Cabang'}]`,
                        asset.id,
                        true,
                        true
                    );
                    $(newOption).attr('data-code', asset.asset_code);
                    $('#asset-select').append(newOption).trigger('change');

                    applyMatchedAsset(asset.id);
                } else {
                    $('#scan-result').html('<span class="text-danger">Aset (' + scannedCode + ') tidak terdaftar!</span>');
                }
            })
            .catch(error => {
                console.error('Scan Error:', error);
                $('#scan-result').html('<span class="text-danger">Aset (' + scannedCode + ') tidak terdaftar!</span>');
            });
        }

        function applyMatchedAsset(assetId) {
            $('#asset-select').val(assetId).trigger('change');
            $('#custom_asset_name').val(''); // Kosongkan input manual jika hasil scan ketemu
            if (html5QrCode) {
                html5QrCode.stop().then(() => {
                    $('#barcodeModal').modal('hide');
                }).catch(err => {
                    $('#barcodeModal').modal('hide');
                });
            } else {
                $('#barcodeModal').modal('hide');
            }
        }
    });
</script>

<style>
    /* Styling agar Select2 menyatu rapi dengan tombol SCAN BARCODE pada Input Group */
    .input-group .select2-container--default .select2-selection--single {
        height: 38px !important;
        border: 1px solid #dee2e6 !important;
        border-top-right-radius: 0 !important;
        border-bottom-right-radius: 0 !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 36px !important;
        color: #212529 !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px !important;
    }
    .input-group > .btn-primary {
        border-top-left-radius: 0 !important;
        border-bottom-left-radius: 0 !important;
        z-index: 2 !important;
    }
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\Documents\IT\ngrok-v3-stable-windows-386\portal\resources\views/tickets/create.blade.php ENDPATH**/ ?>
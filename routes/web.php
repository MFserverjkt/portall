<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\HcLearningController;
use App\Http\Controllers\GuidanceController;
use App\Http\Controllers\ChecklistOutletController;
use App\Http\Controllers\AssetManagementController;

// Import Controller untuk Telegram Bot SDK
use Telegram\Bot\Laravel\Facades\Telegram;

// Import Controller untuk HC Recruitment
use App\Http\Controllers\HC\DashboardController as HcDashboardController;
use App\Http\Controllers\HC\ManpowerRequestController;
use App\Http\Controllers\HC\VacancyController;
use App\Http\Controllers\HC\CandidateController;
use App\Http\Controllers\HC\SlaReportController;
use App\Http\Controllers\HC\AuditLogController;

// 1. Redirect Halaman Utama ke Login
Route::get('/', function () { 
    return redirect()->route('login'); 
});

// 2. Authentication Routes
Route::controller(AuthController::class)->group(function () {
    Route::get('login', 'showLogin')->name('login');
    Route::post('login', 'login')->name('login.post');
    Route::post('logout', 'logout')->name('logout');
});

// ==========================================
// TELEGRAM BOT ROUTES (Publik / Webhook)
// ==========================================

Route::get('/test-telegram', function () {
    try {
        $chatId = '1226395565'; 

        $response = Telegram::sendMessage([
            'chat_id' => $chatId,
            'text'    => 'Halo Hans! Bot Telegram di Portal Maison Feerie berhasil terhubung dengan Laravel! 🚀'
        ]);

        return 'Pesan berhasil dikirim ke Telegram!';
    } catch (\Exception $e) {
        return 'Gagal mengirim pesan: ' . $e->getMessage();
    }
});

Route::post('/telegram/webhook', function () {
    $update = Telegram::commandsHandler(true);

    $chatId = $update->getChat()->getId();
    $text = $update->getText();

    if ($text == '/start') {
        Telegram::sendMessage([
            'chat_id' => $chatId,
            'text'    => 'Halo! Selamat datang di Portal Maison Feerie Bot.'
        ]);
    } else {
        Telegram::sendMessage([
            'chat_id' => $chatId,
            'text'    => 'Pesan Anda diterima: ' . $text
        ]);
    }

    return 'OK';
});


// 3. Protected Routes (Wajib Login)
Route::middleware(['auth'])->group(function () {

    // --- ROUTE AMAN STREAMING LAMPIRAN STORAGE (BEBAS 403 & 404) ---
    Route::get('/view-attachment', function (Request $request) {
        $path = $request->query('path');
        $fullPath = storage_path('app/public/' . $path);
        
        if (!$path || !file_exists($fullPath)) {
            abort(404, 'File lampiran tidak ditemukan.');
        }
        
        return response()->file($fullPath);
    })->name('view.attachment');

    // --- FITUR PANDUAN / GUIDANCE (SEMUA ROLE) ---
    Route::get('guidance', [GuidanceController::class, 'index'])->name('guidance.index');
    Route::get('panduan', [GuidanceController::class, 'index'])->name('panduan.index');

    // --- FITUR ASSET MANAGEMENT ---
    Route::resource('asset-management', AssetManagementController::class);
    Route::post('asset-management/{id}/user-history', [AssetManagementController::class, 'storeUserHistory'])->name('asset-management.user-history.store');
    Route::post('asset-management/{id}/maintenance-history', [AssetManagementController::class, 'storeMaintenanceHistory'])->name('asset-management.maintenance-history.store');

    // --- FITUR ADMIN, IT & MAINTENANCE (USER, ROLE MANAGEMENT, EXPORT EXCEL & CHECKLIST OUTLET) ---
    Route::middleware(['role:ADMIN,IT,MAINTENANCE'])->group(function () {
        Route::controller(UserController::class)->group(function () {
            Route::get('users/export', 'exportExcel')->name('users.export');
            Route::get('user-roles', 'roles')->name('users.roles');
            Route::post('user-roles/update', 'updateRolePermissions')->name('users.roles.update');
            Route::resource('users', UserController::class);
        });

        Route::controller(ChecklistOutletController::class)->prefix('it')->name('it.')->group(function () {
            Route::get('checklist-outlet', 'create')->name('checklist.create');
            Route::post('checklist-outlet', 'store')->name('checklist.store');
        });
    });

    // --- FITUR HC LEARNING ---
    Route::middleware(['role:ADMIN,IT,HC,OUTLET'])->prefix('hc')->controller(HcLearningController::class)->group(function () {
        Route::get('e-learning', 'index')->name('elearning.index');
        Route::get('e-learning-alt', 'index')->name('hc.elearning.index');
        
        // Pre-Test
        Route::get('pre-test', 'pretest')->name('pretest.index');
        Route::get('pre-test-alt', 'pretest')->name('hc.pretest.index');
        Route::post('pre-test', 'storePretest')->name('pretest.store');
        Route::post('pre-test-alt', 'storePretest')->name('hc.pretest.store');
        
        // Post-Test
        Route::get('post-test', 'posttest')->name('posttest.index');
        Route::get('post-test-alt', 'posttest')->name('hc.posttest.index');
        Route::post('post-test', 'storePosttest')->name('posttest.store');
        Route::post('post-test-alt', 'storePosttest')->name('hc.posttest.store');
    });
    
    // --- FITUR HC RECRUITMENT & MANPOWER PLANNING ---
    Route::middleware(['role:ADMIN,IT,HC,MANAGEMENT'])->prefix('hc')->name('hc.')->group(function () {
        Route::get('dashboard', [HcDashboardController::class, 'index'])->name('dashboard');
        Route::resource('manpower', ManpowerRequestController::class);
        Route::resource('vacancies', VacancyController::class);
        Route::resource('candidates', CandidateController::class);
        Route::get('reports/sla', [SlaReportController::class, 'index'])->name('reports.sla');
        Route::get('audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
    });

    // --- FITUR REPORT CORRECTIVE ---
    Route::middleware(['role:ADMIN,IT,MAINTENANCE'])->prefix('report')->name('report.')->controller(ReportController::class)->group(function () {
        Route::get('it', 'reportIt')->name('it');
        Route::get('it/export', 'exportItExcel')->name('it.export');
        Route::get('maintenance', 'reportMaintenance')->name('maintenance');
        Route::get('maintenance/export', 'exportMaintenanceExcel')->name('maintenance.export');
    });

    // --- FITUR INVENTORI ASSET ---
    Route::middleware(['role:ADMIN,IT,MAINTENANCE,OUTLET'])->group(function () {
        Route::controller(AssetController::class)->group(function () {
            Route::match(['get', 'post'], 'assets/scan-check', 'scanCheck')->name('assets.scan-check');
            Route::get('assets/export', 'export')->name('assets.export');
            Route::post('assets/import', 'import')->name('assets.import');
            Route::delete('assets/bulk-delete', 'bulkDelete')->name('assets.bulk-delete');
        });

        Route::resource('assets', AssetController::class);
    });

    // --- FITUR WORK PROGRESS & BAST ---
    Route::middleware(['role:ADMIN,IT,MAINTENANCE'])->controller(TicketController::class)->group(function () {
        Route::patch('tickets/{id}/work', 'updateWork')->name('tickets.work.update');
        Route::get('tickets/{id}/bast/create', 'createBast')->name('tickets.bast.create');
        Route::post('tickets/{id}/bast', 'storeBast')->name('tickets.bast.store');
        Route::get('tickets/{id}/bast-create-alias', 'createBast')->name('tickets.createBast');
        Route::get('tickets/{id}/bast-create-alt', 'createBast')->name('tickets.bast_create');
        Route::post('tickets/{id}/bast-store-alias', 'storeBast')->name('tickets.storeBast');
    });

    // --- FITUR TIKET & DASHBOARD ---
    Route::middleware(['role:ADMIN,IT,MAINTENANCE,OUTLET'])->controller(TicketController::class)->group(function () {
        // Dashboard Enterprise Tiket
        Route::get('tickets/dashboard', 'dashboard')->name('tickets.dashboard');
        
        // Dashboard SLA & Ticket Trend Baru
        Route::get('tickets/sla-dashboard', 'slaDashboard')->name('tickets.sla-dashboard');

        // CRUD Tiket
        Route::get('tickets', 'index')->name('tickets.index');
        Route::get('tickets/create', 'create')->name('tickets.create');
        Route::post('tickets', 'store')->name('tickets.store');
        Route::get('tickets/{id}', 'show')->name('tickets.show');
        
        // Hapus Tiket (Khusus Admin / IT)
        Route::delete('tickets/{id}', 'destroy')->name('tickets.destroy');

        // Status Done Confirmation
        Route::match(['post', 'patch'], 'tickets/{id}/done', 'markAsDone')->name('tickets.markAsDone');
        Route::match(['post', 'patch'], 'tickets/{id}/done-alias', 'markAsDone')->name('tickets.done');
    });

});
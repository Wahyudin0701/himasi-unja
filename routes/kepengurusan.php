<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Kepengurusan\KadivDashboardController;
use App\Http\Controllers\Kepengurusan\KadivProkerController;

// Akan diisi di Sprint 4 & 5
Route::prefix('kepengurusan')->middleware(['auth'])->group(function () {
    // Route::resource('periods', PeriodController::class);
    // Route::resource('divisions', DivisionController::class);
    // Buku Direktori (Global)
    Route::get('/directory', [\App\Http\Controllers\Kepengurusan\DirectoryController::class, 'index'])->name('kepengurusan.directory.index');

    // API Routes untuk Frontend Dinamis
    Route::get('/api/divisions/{division}/members', [\App\Http\Controllers\Kepengurusan\KadivProkerController::class, 'getDivisionMembers'])->name('kepengurusan.api.divisions.members');

    // Kahim & Wakahim Routes
    Route::prefix('kahim')->middleware(['role:kahim,wakahim'])->name('kepengurusan.kahim.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Kepengurusan\KahimDashboardController::class, 'index'])->name('dashboard');
        Route::get('/activities', [\App\Http\Controllers\Kepengurusan\KahimDashboardController::class, 'activities'])->name('activities');
        Route::get('/agendas', [\App\Http\Controllers\Kepengurusan\KahimDashboardController::class, 'agendas'])->name('agendas');
    });

    // Dewan Penasihat (DP) Routes
    Route::prefix('dp')->middleware(['role:dp'])->name('kepengurusan.dp.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Kepengurusan\DpDashboardController::class, 'index'])->name('dashboard');
    });

    // Sekretaris Routes
    Route::prefix('sekretaris')->middleware(['role:sekretaris,kahim,wakahim'])->name('kepengurusan.sekretaris.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Kepengurusan\SekretarisDashboardController::class, 'index'])->name('dashboard');
        Route::get('/arsip-surat', [\App\Http\Controllers\Kepengurusan\Sekretaris\ArsipSuratController::class, 'index'])->name('arsip_surat.index');
        Route::post('/arsip-surat/{letter}/approve', [\App\Http\Controllers\Kepengurusan\Sekretaris\ArsipSuratController::class, 'approve'])->name('arsip_surat.approve');
        Route::post('/arsip-surat/{letter}/revision', [\App\Http\Controllers\Kepengurusan\Sekretaris\ArsipSuratController::class, 'requestRevision'])->name('arsip_surat.revision');
        
        // Himpunan Letters
        Route::resource('organization-letters', \App\Http\Controllers\Kepengurusan\OrganizationLetterController::class)->except(['create', 'show', 'edit']);
        // Document Templates
        Route::resource('templates', \App\Http\Controllers\Kepengurusan\DocumentTemplateController::class)->except(['create', 'show', 'edit']);
        // Vital Archives
        Route::resource('archives', \App\Http\Controllers\Kepengurusan\VitalArchiveController::class)->except(['create', 'show', 'edit']);
        // Meetings
        Route::resource('meetings', \App\Http\Controllers\Kepengurusan\MeetingController::class)->except(['create', 'edit']);
        Route::put('meetings/{meeting}/minutes', [\App\Http\Controllers\Kepengurusan\MeetingController::class, 'updateMinutes'])->name('meetings.updateMinutes');
        Route::put('meetings/{meeting}/attendance', [\App\Http\Controllers\Kepengurusan\MeetingController::class, 'updateAttendance'])->name('meetings.updateAttendance');
    });

    // Bendahara Routes
    Route::prefix('bendahara')->middleware(['role:bendahara'])->name('kepengurusan.bendahara.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Kepengurusan\BendaharaDashboardController::class, 'index'])->name('dashboard');
        
        // Fitur Kas & Transaksi
        Route::resource('finances', \App\Http\Controllers\Kepengurusan\FinanceTransactionController::class)->except(['create', 'show', 'edit']);
        Route::get('/laporan', [\App\Http\Controllers\Kepengurusan\BendaharaDashboardController::class, 'laporan'])->name('laporan');
        Route::get('/laporan/cetak', [\App\Http\Controllers\Kepengurusan\BendaharaDashboardController::class, 'cetakLaporan'])->name('laporan.cetak');
    });

    // Anggota Routes
    Route::prefix('anggota')->middleware(['role:anggota'])->name('kepengurusan.anggota.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Kepengurusan\AnggotaDashboardController::class, 'index'])->name('dashboard');
        
        // Jurnal Proker Non-Event (Hanya proker divisi sendiri)
        Route::get('/proker', [\App\Http\Controllers\Kepengurusan\Anggota\AnggotaProkerController::class, 'index'])->name('proker.index');
    });

    // Jurnal Proker Workspace (Bisa diakses Anggota & Kadiv untuk keperluan Kolaborasi Lintas Divisi)
    Route::prefix('anggota')->middleware(['role:anggota,kadiv'])->name('kepengurusan.anggota.')->group(function () {
        Route::get('/proker/{proker}', [\App\Http\Controllers\Kepengurusan\Anggota\AnggotaProkerController::class, 'show'])->name('proker.show');
        Route::get('/proker/{proker}/progress', [\App\Http\Controllers\Kepengurusan\Anggota\AnggotaProkerController::class, 'progress'])->name('proker.progress');
        Route::post('/proker/{proker}/logs', [\App\Http\Controllers\Kepengurusan\Anggota\AnggotaProkerController::class, 'storeLog'])->name('proker.logs.store');
    });

    // Kadiv Routes
    Route::prefix('kadiv')->middleware(['role:kadiv'])->name('kepengurusan.kadiv.')->group(function () {
        Route::get('/dashboard', [KadivDashboardController::class, 'index'])->name('dashboard');
        Route::get('/progres-divisi', [KadivDashboardController::class, 'progresDivisi'])->name('progres-divisi');
        Route::get('/proker', [KadivProkerController::class, 'index'])->name('proker.index');
        Route::get('/proker/create', [KadivProkerController::class, 'create'])->name('proker.create');
        Route::post('/proker', [KadivProkerController::class, 'store'])->name('proker.store');
        Route::get('/proker/{proker}', [KadivProkerController::class, 'show'])->name('proker.show');
        Route::get('/proker/{proker}/progress', [KadivProkerController::class, 'progress'])->name('proker.progress');
        Route::get('/proker/{proker}/progress/divisi/{division}', [KadivProkerController::class, 'divisionProgress'])->name('proker.division-progress');
        Route::get('/proker/{proker}/edit', [KadivProkerController::class, 'edit'])->name('proker.edit');
        Route::put('/proker/{proker}', [KadivProkerController::class, 'update'])->name('proker.update');
        Route::patch('/proker/{proker}/cancel', [KadivProkerController::class, 'cancel'])->name('proker.cancel');
        

    });

    // Messages Routes
    Route::prefix('messages')->name('messages.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Messaging\MessageController::class, 'index'])->name('index');
        Route::post('/create-channel', [\App\Http\Controllers\Messaging\MessageController::class, 'storeChannel'])->name('channel.store');
        Route::put('/channel/{channel}/members', [\App\Http\Controllers\Messaging\MessageController::class, 'updateChannelMembers'])->name('channel.update_members');
        Route::delete('/channel/{channel}', [\App\Http\Controllers\Messaging\MessageController::class, 'destroyChannel'])->name('channel.destroy');
        Route::get('/download/{message}', [\App\Http\Controllers\Messaging\MessageController::class, 'download'])->name('download');
        Route::get('/{channel}', [\App\Http\Controllers\Messaging\MessageController::class, 'show'])->name('show');
        Route::post('/{channel}', [\App\Http\Controllers\Messaging\MessageController::class, 'store'])->name('store');
    });
});

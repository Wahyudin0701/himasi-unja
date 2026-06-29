<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdmin\DashboardController;
use App\Http\Controllers\SuperAdmin\PeriodController;

Route::middleware(['auth', 'role:super_admin'])->prefix('super-admin')->name('super_admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Manajemen Periode
    Route::get('/periods', [PeriodController::class, 'index'])->name('periods.index');
    Route::get('/periods/create', [PeriodController::class, 'create'])->name('periods.create');
    Route::post('/periods', [PeriodController::class, 'store'])->name('periods.store');
    
    // Arsip Detail Periode
    Route::get('/periods/{period}', [PeriodController::class, 'show'])->name('periods.show');
    Route::get('/periods/{period}/structure', [PeriodController::class, 'structure'])->name('periods.structure');
    Route::get('/periods/{period}/performance', [PeriodController::class, 'performance'])->name('periods.performance');
    Route::get('/periods/{period}/performance/{division}', [PeriodController::class, 'performanceDivision'])->name('periods.performance.division');
    Route::get('/periods/{period}/performance/proker/{proker}', [PeriodController::class, 'performanceProker'])->name('periods.performance.proker');
    // Kepengurusan Aktif (Member & SubDivision)
    Route::get('/active-members', [\App\Http\Controllers\SuperAdmin\MemberController::class, 'index'])->name('members.index');
    Route::get('/active-members/pembina/create', [\App\Http\Controllers\SuperAdmin\MemberController::class, 'createPembina'])->name('members.create.pembina');
    Route::post('/active-members/pembina', [\App\Http\Controllers\SuperAdmin\MemberController::class, 'storePembina'])->name('members.store.pembina');
    Route::get('/active-members/dp/create', [\App\Http\Controllers\SuperAdmin\MemberController::class, 'createDp'])->name('members.create.dp');
    Route::post('/active-members/dp', [\App\Http\Controllers\SuperAdmin\MemberController::class, 'storeDp'])->name('members.store.dp');
    Route::get('/active-members/create', [\App\Http\Controllers\SuperAdmin\MemberController::class, 'create'])->name('members.create');
    Route::post('/active-members', [\App\Http\Controllers\SuperAdmin\MemberController::class, 'store'])->name('members.store');
    Route::get('/active-members/{id}/edit', [\App\Http\Controllers\SuperAdmin\MemberController::class, 'edit'])->name('members.edit');
    Route::put('/active-members/{id}', [\App\Http\Controllers\SuperAdmin\MemberController::class, 'update'])->name('members.update');
    Route::delete('/active-members/{id}', [\App\Http\Controllers\SuperAdmin\MemberController::class, 'destroy'])->name('members.destroy');

    Route::get('/active-divisions/{division}/sub-divisions', [\App\Http\Controllers\SuperAdmin\SubDivisionController::class, 'index'])->name('sub_divisions.index');
    Route::post('/active-divisions/{division}/sub-divisions', [\App\Http\Controllers\SuperAdmin\SubDivisionController::class, 'store'])->name('sub_divisions.store');
    Route::put('/active-sub-divisions/{subDivision}', [\App\Http\Controllers\SuperAdmin\SubDivisionController::class, 'update'])->name('sub_divisions.update');
    Route::delete('/active-sub-divisions/{subDivision}', [\App\Http\Controllers\SuperAdmin\SubDivisionController::class, 'destroy'])->name('sub_divisions.destroy');
});

<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EditController;
use App\Http\Controllers\KmController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\RekapController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AktivitasController;
use App\Http\Controllers\KelasController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'form'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/', [MonitoringController::class, '__invoke'])->name('monitoring')->middleware('role:admin,guru');
    Route::get('/km', [KmController::class, 'index'])->name('km')->middleware('role:admin,km');
    Route::post('/km', [KmController::class, 'simpan'])->middleware('role:admin,km');
    Route::get('/edit', [EditController::class, 'index'])->name('edit')->middleware('role:admin,guru');
    Route::post('/edit', [EditController::class, 'update'])->middleware('role:admin,guru');
    Route::post('/kelas', [KelasController::class, 'store'])->name('kelas.store')->middleware('role:admin,guru');
    Route::put('/kelas/{kelas}', [KelasController::class, 'update'])->name('kelas.update')->middleware('role:admin,guru');
    Route::delete('/kelas/{kelas}', [KelasController::class, 'destroy'])->name('kelas.destroy')->middleware('role:admin,guru');
    Route::get('/rekap', [RekapController::class, '__invoke'])->name('rekap')->middleware('role:admin');
    Route::get('/siswa', [SiswaController::class, 'index'])->name('siswa')->middleware('role:admin');
    Route::post('/siswa', [SiswaController::class, 'store'])->middleware('role:admin');
    Route::post('/siswa/hapus', [SiswaController::class, 'destroy'])->name('siswa.hapus')->middleware('role:admin');
    Route::get('/siswa/export', [SiswaController::class, 'export'])->name('siswa.export')->middleware('role:admin');
    Route::post('/siswa/import', [SiswaController::class, 'import'])->name('siswa.import')->middleware('role:admin');
    Route::get('/users', [UserController::class, 'index'])->name('users')->middleware('role:admin');
    Route::post('/users', [UserController::class, 'store'])->middleware('role:admin');
    Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset')->middleware('role:admin');
    Route::get('/aktivitas', [AktivitasController::class, 'index'])->name('aktivitas')->middleware('role:admin');
    Route::match(['get', 'post'], '/api', [ApiController::class, 'index'])->name('api');
});

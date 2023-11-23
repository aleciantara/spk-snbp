<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\RaporController;
use App\Http\Controllers\PrestasiController;
use App\Http\Controllers\SPKController;
use App\Http\Controllers\SuratController;
use App\Http\Controllers\UserSiswaController;
use Illuminate\Support\Facades\Route;

use App\Models\Siswa;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::middleware(['guest'])->group(function(){
    Route::get('/', [SessionController::class, 'index'])->name('login');
    Route::post('/', [SessionController::class, 'login']);
});

Route::get('/home', function(){
    return redirect('/dashboard');
} );

Route::middleware(['auth'])->group(function(){
    Route::middleware(['userAccess:admin'])->group(function () {

        Route::get('/admin/editPassword', [AdminController::class, 'editPassword'])->name('admin.editPassword');
        Route::put('/admin/updatePassword', [AdminController::class, 'updatePassword'])->name('admin.updatePassword');

        Route::get('/siswa', [SiswaController::class, 'index'])->name('siswa.index');
        Route::get('/siswa/create', [SiswaController::class, 'create'])->name('siswa.create');
        Route::post('/siswa/store', [SiswaController::class, 'store'])->name('siswa.store');
        Route::get('/siswa/edit/{nisn}', [SiswaController::class, 'edit'])->name('siswa.edit');
        Route::put('/siswa/update/{nisn}', [SiswaController::class, 'update'])->name('siswa.update');
        Route::get('siswa/prestasi/{nisn}', [SiswaController::class, 'prestasi'])->name('siswa.prestasi');
        Route::delete('/siswa/{siswa}', [SiswaController::class, 'destroy'])->name('siswa.destroy');

        Route::get('/siswa/import', [SiswaController::class, 'import'])->name('siswa.import');
        Route::post('/siswa/import', [SiswaController::class, 'import']);

        Route::get('/rapor/edit/{nisn}', [RaporController::class, 'edit'])->name('rapor.edit');
        Route::put('/rapor/update/{nisn}', [RaporController::class, 'update'])->name('rapor.update');

        Route::get('/prestasi', [PrestasiController::class, 'index'])->name('prestasi.index');
        Route::get('/prestasi/create/{siswa?}', [PrestasiController::class, 'create'])->name('prestasi.create');
        Route::post('/prestasi/store', [PrestasiController::class, 'store'])->name('prestasi.store');
        Route::get('/prestasi/edit/{id}', [PrestasiController::class, 'edit'])->name('prestasi.edit');
        Route::put('/prestasi/update/{id}', [PrestasiController::class, 'update'])->name('prestasi.update');
        Route::delete('/prestasi/{prestasi}', [PrestasiController::class, 'destroy'])->name('prestasi.destroy');

        Route::get('/spk', [SPKController::class, 'index'])->name('spk.index');
        Route::get('/spkPrint', [SPKController::class, 'print'])->name('spk.print');
        Route::get('/spkKriteria', [SPKController::class, 'kriteria'])->name('spk.kriteria');
        Route::get('/spkNormalisasi', [SPKController::class, 'normalisasi'])->name('spk.normalisasi');

        Route::get('/surat', [SuratController::class, 'index'])->name('surat.index');
        Route::post('/storeTemplateSurat', [SuratController::class, 'storeTemplateSurat'])->name('surat.storeTemplateSurat');
        Route::delete('/destroy/{template}', [SuratController::class, 'destroyTemplate'])->name('surat.destroyTemplate');
        Route::delete('/destroy/{surat}', [SuratController::class, 'destroySurat'])->name('surat.destroySurat');
        Route::get('/surat/edit/{id}', [SuratController::class, 'editSurat'])->name('surat.editSurat');
        Route::put('/surat/update/{id}', [SuratController::class, 'updateSurat'])->name('surat.updateSurat');


        Route::put('/setBobot', [SPKController::class, 'setBobot'])->name('spk.setBobot');
        Route::put('/setKuota', [SPKController::class, 'setKuota'])->name('spk.setKuota');

    });

    

    Route::middleware(['userAccess:siswa'])->group(function () {
        Route::get('/siswa/read', [UserSiswaController::class, 'readSiswa'])->name('UserSiswa.read');
        Route::get('/rapor/read', [UserSiswaController::class, 'readRaporSiswa'])->name('rapor.read');
        Route::get('/prestasi/read', [UserSiswaController::class, 'readPrestasiSiswa'])->name('prestasi.read');
        
        Route::get('/prestasi/ajukan', [UserSiswaController::class, 'ajukanPrestasiSiswa'])->name('prestasi.ajukan');
        Route::post('/prestasi/storePengajuanPrestasi', [UserSiswaController::class, 'storePengajuanPrestasi'])->name('prestasi.storePengajuanPrestasi');
        
        Route::get('/spk/read', [UserSiswaController::class, 'readSpk'])->name('spk.read');

        Route::get('/editPassword', [UserSiswaController::class, 'editPassword'])->name('userSiswa.editPassword');
        Route::put('/updatePassword', [UserSiswaController::class, 'updatePassword'])->name('userSiswa.updatePassword');

        Route::get('/templateSurat/download/{template}', [SuratController::class, 'downloadTemplate'])->name('surat.downloadTemplate');
        Route::post('/storeSurat', [SuratController::class, 'storeSurat'])->name('surat.storeSurat');
        // Route::get('siswa/prestasi/{nisn}', [SiswaController::class, 'prestasi'])->name('siswa.prestasi');
        // Route::get('/prestasi/create/{siswa?}', [PrestasiController::class, 'create'])->name('prestasi.create');
        // Route::post('/prestasi/store', [PrestasiController::class, 'store'])->name('prestasi.store');
        // Route::get('/editPassword', [UserSiswaController::class, 'editPassword'])->name('siswa.editPassword');
        // Route::put('/updatePassword', [UserSiswaController::class, 'updatePassword'])->name('siswa.updatePassword');
    });

    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/surat', [SuratController::class, 'index'])->name('surat.index');
    Route::get('/surat/download/{surat}', [SuratController::class, 'downloadSurat'])->name('surat.downloadSurat');
    Route::get('/templateSurat/download/{template}', [SuratController::class, 'downloadTemplate'])->name('surat.downloadTemplate');


    Route::get('/logout', [SessionController::class, 'logout']);  
    // Route::fallback(function () {
    //     abort(404, 'Not Found');
    // });
    
});






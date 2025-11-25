<?php

use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserAccessController;
use App\Http\Controllers\Produksi\InputCounterController;
use App\Http\Controllers\Produksi\LokasiBarangJadiController;
use App\Http\Controllers\Produksi\SisaStokBahanBakuController;
use App\Http\Controllers\Produksi\TargetHarianController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Qc\SpkuController;
use App\Http\Controllers\Teknik\KerusakanMesinController;
use App\Http\Controllers\Teknik\NamaMesinController;
use App\Http\Controllers\Teknik\PerbaikanTeknikController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Support\Facades\Route;


// ===============================
// HALAMAN UTAMA
// ===============================
Route::get('/', function () {
    return view('welcome');
});


// ===============================
// DASHBOARD (hanya user login + verified)
// ===============================
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// ===============================
// PROFILE USER
// ===============================
// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });


// ===============================
// ADMIN PANEL (Role & Permission Management)
// Semua di-protect dengan auth
// Middleware Spatie dipasang per route
// ===============================
Route::middleware('auth')->group(function () {

    // ROLE
    Route::get('/roles', [RoleController::class, 'index'])
        ->middleware('permission:view roles')
        ->name('roles.index');

    Route::get('/roles/create', [RoleController::class, 'create'])
        ->middleware('permission:create roles')
        ->name('roles.create');

    Route::post('/roles', [RoleController::class, 'store'])
        ->middleware('permission:create roles')
        ->name('roles.store');

    Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])
        ->middleware('permission:edit roles')
        ->name('roles.edit');

    Route::put('/roles/{role}', [RoleController::class, 'update'])
        ->middleware('permission:edit roles')
        ->name('roles.update');

    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])
        ->middleware('permission:delete roles')
        ->name('roles.destroy');


    // USER MANAGEMENT

    Route::get('/mgusers', [UserController::class, 'index'])
        ->middleware('permission:user-view')
        ->name('users.index');

    Route::get('/mgusers/create', [UserController::class, 'create'])
        ->middleware('permission:user-create')
        ->name('users.create');

    Route::post('/mgusers', [UserController::class, 'store'])
        ->middleware('permission:user-create')
        ->name('users.store');

    Route::get('/mgusers/{user}/edit', [UserController::class, 'edit'])
        ->middleware('permission:user-edit')
        ->name('users.edit');

    Route::put('/mgusers/{user}', [UserController::class, 'update'])
        ->middleware('permission:user-edit')
        ->name('users.update');

    Route::delete('/mgusers/{user}', [UserController::class, 'destroy'])
        ->middleware('permission:user-delete')
        ->name('users.destroy');

    Route::prefix('produksi/')->group(function () {

        ///////////////////   TARGET HARIAN   /////////////
        // INDEX
        Route::get('/targetharian/index', [TargetHarianController::class, 'index'])
            ->middleware('permission:target_harian_view')
            ->name('produksi.targetharian.index');
        // CREATE
        Route::get('/targetharian/create', [TargetHarianController::class, 'create'])
            ->middleware('permission:target_harian_view')
            ->name('produksi.targetharian.create');
        // STORE
        Route::post('/targetharian/store', [TargetHarianController::class, 'store'])
            ->middleware('permission:target_harian_view')
            ->name('produksi.targetharian.store');
        // EDIT
        Route::get('/targetharian/edit/{tgl_prod}', [TargetHarianController::class, 'edit'])
            ->middleware('permission:target_harian_view')
            ->name('produksi.targetharian.edit');
        // UPDATE
        Route::put('/targetharian/update/{tgl_prod}', [TargetHarianController::class, 'update'])
            ->middleware('permission:target_harian_view')
            ->name('produksi.targetharian.update');
        // DELETE
        Route::delete(
            '/targetharian/delete/{tgl_prod}',
            [TargetHarianController::class, 'destroy']
        )
            ->middleware('permission:target_harian_view')
            ->name('produksi.targetharian.destroy');

        /////////////////////////////  INPUT COUNTER   ///////////
        // INDEX
        Route::get('/inputcounter/index', [InputCounterController::class, 'index'])
            ->middleware('permission:input_counter_view')
            ->name('produksi.inputcounter.index');
        // LIST
        Route::post('/inputcounter/list', [InputCounterController::class, 'list'])
            ->middleware('permission:input_counter_view')
            ->name('produksi.inputcounter.list');
        // CREATE
        Route::get('/inputcounter/create', [InputCounterController::class, 'create'])
            ->middleware('permission:input_counter_create')
            ->name('produksi.inputcounter.create');
        // STORE
        Route::post('/inputcounter/store', [InputCounterController::class, 'store'])
            ->middleware('permission:input_counter_create')
            ->name('produksi.inputcounter.store');
        // EDIT
        Route::get('/inputcounter/edit/{tanggal}/{spk_nomor}/{line}/{shift}/{no_reg}/{jenis}', [InputCounterController::class, 'edit'])
            ->middleware('permission:input_counter_edit')
            ->name('produksi.inputcounter.edit');
        // UPDATE
        Route::put('/inputcounter/update/{tanggal}/{spk_nomor}/{line}/{shift}/{no_reg}/{jenis}', [InputCounterController::class, 'update'])
            ->middleware('permission:input_counter_delete')
            ->name('produksi.inputcounter.update');
        // DELETE
        Route::delete(
            '/inputcounter/delete/{tanggal}/{spk_nomor}/{line}/{shift}/{no_reg}/{jenis}',
            [InputCounterController::class, 'delete']
        )
            ->middleware('permission:input_counter_view')
            ->name('produksi.inputcounter.delete');

        /////////////////////////////  SISA STOKJ BAHAN BAKU   ///////////
        // INDEX
        Route::get('/sisastokbahanbaku/index', [SisaStokBahanBakuController::class, 'index'])
            ->middleware('permission:sisa_stok_bahan_baku_view')
            ->name('produksi.sisastokbahanbaku.index');
        // LIST
        Route::post('/sisastokbahanbaku/list', [SisaStokBahanBakuController::class, 'list'])
            ->middleware('permission:sisa_stok_bahan_baku_view')
            ->name('produksi.sisastokbahanbaku.list');
        // CREATE
        Route::get('/sisastokbahanbaku/create', [SisaStokBahanBakuController::class, 'create'])
            ->middleware('permission:sisa_stok_bahan_baku_create')
            ->name('produksi.sisastokbahanbaku.create');
        // STORE
        Route::post('/sisastokbahanbaku/store', [SisaStokBahanBakuController::class, 'store'])
            ->middleware('permission:sisa_stok_bahan_baku_create')
            ->name('produksi.sisastokbahanbaku.store');
        // EDIT
        Route::get('/sisastokbahanbaku/edit/{tanggal}', [SisaStokBahanBakuController::class, 'edit'])
            ->middleware('permission:sisa_stok_bahan_baku_edit')
            ->name('produksi.sisastokbahanbaku.edit');
        // UPDATE
        Route::put('/sisastokbahanbaku/update/{tanggal}', [SisaStokBahanBakuController::class, 'update'])
            ->middleware('permission:sisa_stok_bahan_baku_delete')
            ->name('produksi.sisastokbahanbaku.update');
        // DELETE
        Route::delete(
            '/sisastokbahanbaku/delete/{tanggal}',
            [SisaStokBahanBakuController::class, 'destroy']
        )
            ->middleware('permission:sisa_stok_bahan_baku_view')
            ->name('produksi.sisastokbahanbaku.destroy');
        //auto
        Route::post('sisastokbahanbaku/spkfromview', [SisaStokBahanBakuController::class, 'spkfromview'])->name('produksi.sisastokbahanbaku.spkfromview');

        ///////////////////  LOKASI BARANG JADI   /////////////
        // INDEX
        Route::get('/lokasibarangjadi/index', [LokasiBarangJadiController::class, 'index'])
            ->middleware('permission:target_harian_view')
            ->name('produksi.lokasibarangjadi.index');
        // CREATE
        Route::get('/lokasibarangjadi/create', [LokasiBarangJadiController::class, 'create'])
            ->middleware('permission:target_harian_view')
            ->name('produksi.lokasibarangjadi.create');
        // STORE
        Route::post('/lokasibarangjadi/store', [LokasiBarangJadiController::class, 'store'])
            ->middleware('permission:target_harian_view')
            ->name('produksi.lokasibarangjadi.store');
        // EDIT
        Route::get('/lokasibarangjadi/edit/{tgl_prod}', [LokasiBarangJadiController::class, 'edit'])
            ->middleware('permission:target_harian_view')
            ->name('produksi.lokasibarangjadi.edit');
        // UPDATE
        Route::put('/lokasibarangjadi/update/{tgl_prod}', [LokasiBarangJadiController::class, 'update'])
            ->middleware('permission:target_harian_view')
            ->name('produksi.lokasibarangjadi.update');
        // DELETE
        Route::delete(
            '/lokasibarangjadi/delete/{tgl_prod}',
            [LokasiBarangJadiController::class, 'destroy']
        )
            ->middleware('permission:target_harian_view')
            ->name('produksi.lokasibarangjadi.destroy');
    });
    Route::get('/search-spk', [LokasiBarangJadiController::class, 'searchSpk'])->name('search.spk');
    Route::get('/search-spk-superker', [LokasiBarangJadiController::class, 'searchSpksuperker'])->name('search.spksuperker');


    //////////////////////
    /// QC  ////
    /////////////////
    Route::prefix('qc/')->group(function () {
        Route::get('/autocomplete-spk', [spkuController::class, 'autocomplete'])->name('autocomplete.spk');

        ///////////////////   TARGET HARIAN   /////////////
        // INDEX
        Route::get('/spku/index', [SpkuController::class, 'index'])
            ->middleware('permission:spku_view')
            ->name('qc.spku.index');

        // CREATE
        Route::get('/spku/create', [SpkuController::class, 'create'])
            ->middleware('permission:spku_create')
            ->name('qc.spku.create');

        Route::post('/spku/store', [SpkuController::class, 'store'])
            ->middleware('permission:spku_create')
            ->name('qc.spku.store');

        // EDIT / UPDATE
        Route::get('/spku/{id}/edit', [SpkuController::class, 'edit'])
            ->middleware('permission:spku_edit')
            ->name('qc.spku.edit');

        Route::put('/spku/{id}/update', [SpkuController::class, 'update'])
            ->middleware('permission:spku_edit')
            ->name('qc.spku.update');

        // DELETE
        Route::delete('/spku/delete{id}', [SpkuController::class, 'destroy'])
            ->middleware('permission:spku_delete')
            ->name('qc.spku.destroy');

        // laporan/rekap
        Route::get('/spku/laporan', [SpkuController::class, 'laporan'])
            ->middleware('permission:spku_view')
            ->name('qc.spku.laporan');
        //show
        Route::get('/spku/show/{id}', [SpkuController::class, 'show'])
            ->middleware('permission:spku_view')
            ->name('qc.spku.show');
    });


    // ===============================
    // TEKNIK - KERUSAKAN MESIN
    // ===============================

    Route::prefix('teknik/kerusakanmesin')->group(function () {

        // INDEX
        Route::get('/', [KerusakanMesinController::class, 'index'])
            ->middleware('permission:kerusakan_mesin_view')
            ->name('teknik.kerusakanmesin.index');

        // CREATE
        Route::get('/create', [KerusakanMesinController::class, 'create'])
            ->middleware('permission:kerusakan_mesin_create')
            ->name('teknik.kerusakanmesin.create');

        Route::post('/', [KerusakanMesinController::class, 'store'])
            ->middleware('permission:kerusakan_mesin_create')
            ->name('teknik.kerusakanmesin.store');

        // EDIT / UPDATE
        Route::get('/{id}/edit', [KerusakanMesinController::class, 'edit'])
            ->middleware('permission:kerusakan_mesin_edit')
            ->name('teknik.kerusakanmesin.edit');

        Route::put('/{id}/update', [KerusakanMesinController::class, 'update'])
            ->middleware('permission:kerusakan_mesin_edit')
            ->name('teknik.kerusakanmesin.update');

        // DELETE
        Route::delete('/{id}', [KerusakanMesinController::class, 'delete'])
            ->middleware('permission:kerusakan_mesin_delete')
            ->name('teknik.kerusakanmesin.delete');
    });
    Route::prefix('teknik/perbaikanteknik')->group(function () {

        // INDEX
        Route::get('/', [PerbaikanTeknikController::class, 'index'])
            ->middleware('permission:perbaikan_teknik_view')
            ->name('teknik.perbaikanteknik.index');

        //show
        Route::get('/{id}/show', [PerbaikanTeknikController::class, 'show'])
            ->middleware('permission:perbaikan_teknik_view')
            ->name('teknik.perbaikanteknik.show');
        // CREATE
        Route::get('/create', [PerbaikanTeknikController::class, 'create'])
            ->middleware('permission:perbaikan_teknik_create')
            ->name('teknik.perbaikanteknik.create');


        Route::post('/', [PerbaikanTeknikController::class, 'store'])
            ->middleware('permission:perbaikan_teknik_create')
            ->name('teknik.perbaikanteknik.store');
        // EDIT / UPDATE
        Route::get('/{id}/edit', [PerbaikanTeknikController::class, 'edit'])
            ->middleware('permission:perbaikan_teknik_edit')
            ->name('teknik.perbaikanteknik.edit');
        Route::put('/{id}/update', [PerbaikanTeknikController::class, 'update'])
            ->middleware('permission:perbaikan_teknik_edit')
            ->name('teknik.perbaikanteknik.update');
        // DELETE
        Route::delete('/{id}', [PerbaikanTeknikController::class, 'destroy'])
            ->middleware('permission:perbaikan_teknik_delete')
            ->name('teknik.perbaikanteknik.delete');
        //excel
        Route::get('/excel/{tahun}', [PerbaikanTeknikController::class, 'excel'])
            ->middleware('permission:perbaikan_teknik_excel')
            ->name('teknik.perbaikanteknik.excel');
        // ========================
        // LAPORAN BULANAN
        // ========================
        Route::get('/laporan/bulanan', [PerbaikanTeknikController::class, 'laporanBulanan'])
            ->middleware('permission:perbaikan_teknik_laporan')
            ->name('teknik.perbaikanteknik.laporan.bulanan');

        // Export Excel Bulanan
        Route::get('/laporan/bulanan/excel/{periode}', [PerbaikanTeknikController::class, 'laporanBulananExcel'])
            ->middleware('permission:perbaikan_teknik_laporan')
            ->name('teknik.perbaikanteknik.laporan.bulanan.excel');

        // ========================
        // LAPORAN TAHUNAN
        // ========================
        Route::get('/laporan/tahunan', [PerbaikanTeknikController::class, 'laporanTahunan'])
            ->middleware('permission:perbaikan_teknik_laporan')
            ->name('teknik.perbaikanteknik.laporan.tahunan');

        // Export Excel Tahunan
        Route::get('/laporan/tahunan/excel', [PerbaikanTeknikController::class, 'laporanTahunanExcel'])
            ->middleware('permission:perbaikan_teknik_laporan')
            ->name('teknik.perbaikanteknik.laporan.tahunan.excel');

        // ========================
        // DAFTAR MASALAH MESIN
        // ========================
        Route::get('/laporan/daftarmasalahmesin', [PerbaikanTeknikController::class, 'laporandaftarmasalahmesin'])
            ->middleware('permission:perbaikan_teknik_laporan')
            ->name('teknik.perbaikanteknik.laporan.daftarmasalahmesin');
        Route::get(
            '/laporan/daftarmasalahmesin_d/{id}',
            [PerbaikanTeknikController::class, 'laporandaftarmasalahmesin_d']
        )
            ->middleware('permission:perbaikan_teknik_laporan')
            ->name('teknik.perbaikanteknik.laporan.daftarmasalahmesin_d');

        // mesin
        Route::get('/mesin/index', [NamaMesinController::class, 'index'])
            ->middleware('permission:perbaikan_teknik_create')
            ->name('teknik.perbaikanteknik.mesin.index');
        Route::get('/mesin/create', [NamaMesinController::class, 'create'])
            ->middleware('permission:perbaikan_teknik_create')
            ->name('teknik.perbaikanteknik.mesin.create');
        Route::post('/mesin/store', [NamaMesinController::class, 'store'])
            ->middleware('permission:perbaikan_teknik_create')
            ->name('teknik.perbaikanteknik.mesin.store');
        Route::get('/mesin/edit/{id}', [NamaMesinController::class, 'edit'])
            ->middleware('permission:perbaikan_teknik_create')
            ->name('teknik.perbaikanteknik.mesin.edit');
        Route::post('/mesin/update/{id}', [NamaMesinController::class, 'update'])
            ->middleware('permission:perbaikan_teknik_create')
            ->name('teknik.perbaikanteknik.mesin.update');

        Route::delete('/mesin/delete/{id}', [NamaMesinController::class, 'delete'])
            ->middleware('permission:perbaikan_teknik_create')
            ->name('teknik.perbaikanteknik.mesin.delete');





        Route::get('/namamesin/get/{kode}', [NamaMesinController::class, 'getByKode'])->name('namamesin.getByKode');
    });


    // ===============================
    // PERMISSIONS
    // ===============================
    Route::resource('mgpermissions', PermissionController::class)
        ->middleware('permission:mgpermissions_view');
});


require __DIR__ . '/auth.php';

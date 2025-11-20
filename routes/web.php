<?php

use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserAccessController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Teknik\KerusakanMesinController;
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
        Route::delete('/{id}', [PerbaikanTeknikController::class, 'delete'])
            ->middleware('permission:perbaikan_teknik_delete')
            ->name('teknik.perbaikanteknik.delete');
    });


    // ===============================
    // PERMISSIONS
    // ===============================
    Route::resource('mgpermissions', PermissionController::class)
        ->middleware('permission:mgpermissions_view');
});


require __DIR__ . '/auth.php';

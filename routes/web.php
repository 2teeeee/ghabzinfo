<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MainController::class, 'index'])->name('main.index');

Route::get('/register', [AuthController::class, 'create'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.create');
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('authenticate');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::prefix('electricity')->group(function () {
        Route::get('/index', [BillController::class, 'electricityBillIndex'])->name('electricity.index');
        Route::post('/store', [BillController::class, 'electricityBillInquire'])->name('electricity.inquire');
        Route::get('/{bill}', [BillController::class, 'electricityBillShow'])->name('electricity.show');
    });

    Route::prefix('gas')->group(function () {
        Route::get('/index', [BillController::class, 'gasBillIndex'])->name('gas.index');
        Route::post('/store', [BillController::class, 'gasBillInquire'])->name('gas.inquire');
        Route::get('/{bill}', [BillController::class, 'gasBillShow'])->name('gas.show');
    });

    Route::prefix('water')->group(function () {
        Route::get('/index', [BillController::class, 'waterBillIndex'])->name('water.index');
        Route::post('/store', [BillController::class, 'waterBillInquire'])->name('water.inquire');
        Route::get('/{bill}', [BillController::class, 'waterBillShow'])->name('water.show');
    });
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::middleware(['role:admin,manager'])->group(function () {
        Route::get('/', [MainController::class, 'admin'])->name('index');
        Route::resource('users', UserController::class);

    });
});


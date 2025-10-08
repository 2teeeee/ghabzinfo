<?php

use App\Http\Controllers\BillController;
use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MainController::class, 'index'])->name('main.index');

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


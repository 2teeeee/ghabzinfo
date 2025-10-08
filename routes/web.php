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


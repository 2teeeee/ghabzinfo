<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CenterController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\ElectricityBillController;
use App\Http\Controllers\GasBillController;
use App\Http\Controllers\HierarchyController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\OrganController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WaterBillController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MainController::class, 'admin'])->middleware(['auth'])->name('main.index');

Route::get('/register', [AuthController::class, 'create'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.create');
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('authenticate');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::middleware(['role:admin,city,organ,unit,center'])->group(function () {
        Route::get('/', [MainController::class, 'admin'])->name('index');
        Route::resource('users', UserController::class);

        Route::prefix('electricity-bills')->name('electricity_bills.')->group(function () {
            Route::get('/', [ElectricityBillController::class, 'index'])->name('index');
            Route::get('/create', [ElectricityBillController::class, 'create'])->name('create');
            Route::post('/store', [ElectricityBillController::class, 'store'])->name('store');
            Route::post('/refresh', [ElectricityBillController::class, 'refresh'])->name('refresh');
            Route::delete('/{id}', [ElectricityBillController::class, 'destroy'])->name('destroy');
            Route::get('/{bill}', [ElectricityBillController::class, 'show'])->name('show');

        });
        Route::prefix('gas-bills')->name('gas_bills.')->group(function () {
            Route::get('/', [GasBillController::class, 'index'])->name('index');
            Route::get('/create', [GasBillController::class, 'create'])->name('create');
            Route::post('/store', [GasBillController::class, 'store'])->name('store');
            Route::post('/refresh', [GasBillController::class, 'refresh'])->name('refresh');
            Route::delete('/{id}', [GasBillController::class, 'destroy'])->name('destroy');
            Route::get('/{bill}', [GasBillController::class, 'show'])->name('show');
        });
        Route::prefix('water-bills')->name('water_bills.')->group(function () {
            Route::get('/', [WaterBillController::class, 'index'])->name('index');
            Route::get('/create', [WaterBillController::class, 'create'])->name('create');
            Route::post('/store', [WaterBillController::class, 'store'])->name('store');
            Route::post('/refresh', [WaterBillController::class, 'refresh'])->name('refresh');
            Route::delete('/{id}', [WaterBillController::class, 'destroy'])->name('destroy');
            Route::get('/{bill}', [WaterBillController::class, 'show'])->name('show');
        });

        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/electricity/last', [ReportController::class, 'electricityBillLastMonth'])->name('electricity.last');
        });

        Route::prefix('api')->group(function () {
            Route::get('organs', [HierarchyController::class, 'organs']);
            Route::get('units', [HierarchyController::class, 'units']);
            Route::get('centers', [HierarchyController::class, 'centers']);
        });
    });

    Route::middleware(['role:admin'])->group(function () {
        Route::resource('cities', CityController::class)->names('cities');
    });

    Route::middleware(['role:admin,city'])->group(function () {
        Route::resource('organs', OrganController::class)->names('organs');
    });

    Route::middleware(['role:admin,city,organ'])->group(function () {
        Route::get('/reports/electricity/dashboard', [ReportController::class, 'electricityDashboard'])->name('reports.electricity.dashboard');
        Route::get('/reports/gas/dashboard', [ReportController::class, 'gasDashboard'])->name('reports.gas.dashboard');
        Route::get('/reports/water/dashboard', [ReportController::class, 'waterDashboard'])->name('reports.water.dashboard');
        Route::resource('units', UnitController::class)->names('units');
    });

    Route::middleware(['role:admin,city,organ,unit'])->group(function () {
        Route::resource('centers', CenterController::class)->names('centers');
    });
});


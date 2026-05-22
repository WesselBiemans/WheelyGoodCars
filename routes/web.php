<?php

use App\Http\Controllers\AdminTagController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/cars', [CarController::class, 'index'])->name('cars.index');
Route::post('/cars/{car}/views', [CarController::class, 'incrementViews'])->name('cars.views.increment');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Car routes
    Route::get('/cars/check-license-plate', [CarController::class, 'checkLicensePlate'])->name('cars.check-license-plate');
    Route::get('/cars/my-cars', [CarController::class, 'myCars'])->name('cars.my-cars');
    Route::resource('cars', CarController::class)->only(['create', 'store', 'destroy']);

    Route::get('/admin/tags', [AdminTagController::class, 'index'])->name('admin.tags.index');
});

require __DIR__.'/auth.php';

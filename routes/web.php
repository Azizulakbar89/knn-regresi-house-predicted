<?php

use App\Models\HouseTesting;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestingController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KnnRegressionController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/training', [TrainingController::class, 'index'])->name('knn.regression');
Route::post('/import-training', [TrainingController::class, 'importTraining'])->name('import.training');
Route::delete('/training/delete-all', [TrainingController::class, 'deleteAll'])->name('training.deleteAll');

Route::get('/testing', [TestingController::class, 'index'])->name('testing.regression');
Route::post('/import-testing', [TestingController::class, 'importTesting'])->name('import.testing');
Route::delete('/testing/delete-all', [TestingController::class, 'deleteAll'])->name('testing.deleteAll');

Route::post('/predict', [TestingController::class, 'predict'])->name('predict');
Route::get('/result', [TestingController::class, 'showResults'])->name('results.show');

Route::get('/', [DashboardController::class, 'showPriceAnalysis'])
    ->name('showPriceAnalysis');  // Add this name
<?php

use App\Http\Controllers\Solitaire\DrawCardController;
use App\Http\Controllers\Solitaire\HintController;
use App\Http\Controllers\Solitaire\MakeMoveController;
use App\Http\Controllers\Solitaire\ResetStockController;
use App\Http\Controllers\Solitaire\SolitaireGameController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::prefix('game')->name('solitaire.')->group(function () {
    Route::post('/', [SolitaireGameController::class, 'store'])->name('store');
    Route::get('/{game}', [SolitaireGameController::class, 'show'])->name('show');
    Route::post('/{game}/move', MakeMoveController::class)->name('move');
    Route::post('/{game}/draw', DrawCardController::class)->name('draw');
    Route::post('/{game}/reset-stock', ResetStockController::class)->name('reset-stock');
    Route::get('/{game}/hint', HintController::class)->name('hint');
});

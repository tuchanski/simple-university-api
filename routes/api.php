<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfessorController;

Route::prefix('professors')->name('professors.')->group(function () {
    Route::get('/', [ProfessorController::class, 'index']);
    Route::get('/{id}', [ProfessorController::class, 'show']);
    Route::post('/', [ProfessorController::class, 'store']);
    Route::delete('/{id}', [ProfessorController::class, 'destroy']);
    Route::patch('/{id}', [ProfessorController::class, 'update']);
});

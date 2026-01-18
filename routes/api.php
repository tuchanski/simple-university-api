<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfessorController;

Route::get('/professors', [ProfessorController::class, 'index']);
Route::get('/professors/{id}', [ProfessorController::class, 'show']);
Route::post('/professors', [ProfessorController::class, 'store']);
Route::delete('/professors/{id}', [ProfessorController::class, 'destroy']);

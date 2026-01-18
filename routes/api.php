<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfessorController;

Route::get('/professors', [ProfessorController::class, 'index']);

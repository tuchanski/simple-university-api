<?php

use App\Http\Controllers\CourseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfessorController;
use App\Http\Controllers\StudentController;

Route::prefix('professors')->name('professors.')->group(function () {
    Route::post('/', [ProfessorController::class, 'store']);
    Route::get('/', [ProfessorController::class, 'index']);
    Route::get('/{id}', [ProfessorController::class, 'show']);
    Route::delete('/{id}', [ProfessorController::class, 'destroy']);
    Route::patch('/{id}', [ProfessorController::class, 'update']);
});

Route::prefix('students')->name('students.')->group(function () {
    Route::get('/', [StudentController::class, 'index']);
    Route::post('/', [StudentController::class, 'store']);
    Route::get('/{id}', [StudentController::class, 'show']);
    Route::delete('/{id}', [StudentController::class, 'destroy']);
    Route::patch('/{id}', [StudentController::class, 'update']);
});

Route::prefix('courses')->name('courses.')->group(function () {
    Route::get('/', [CourseController::class, 'index']);
    Route::post('/', [CourseController::class, 'store']);
    Route::get('/{id}', [CourseController::class, 'show']);
    Route::delete('/{id}', [CourseController::class, 'destroy']);
    Route::patch('/{id}', [CourseController::class, 'update']);
});

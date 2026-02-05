<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfessorController;
use App\Http\Controllers\StudentController;

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api')->name('logout');
    Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:api')->name('refresh');
    Route::post('/me', [AuthController::class, 'me'])->middleware('auth:api')->name('me');
});

Route::prefix('professors')
    ->name('professors.')
    ->middleware('auth:api')
    ->group(function () {
        Route::post('/', [ProfessorController::class, 'store']);
        Route::get('/', [ProfessorController::class, 'index']);
        Route::get('/{id}', [ProfessorController::class, 'show']);
        Route::delete('/{id}', [ProfessorController::class, 'destroy']);
        Route::patch('/{id}', [ProfessorController::class, 'update']);
        Route::get('/{id}/courses', [ProfessorController::class, 'coursesIndex']);
    });

Route::prefix('students')
    ->name('students.')
    ->middleware('auth:api')
    ->group(function () {
    Route::get('/', [StudentController::class, 'index']);
    Route::post('/', [StudentController::class, 'store']);
    Route::get('/{id}', [StudentController::class, 'show']);
    Route::delete('/{id}', [StudentController::class, 'destroy']);
    Route::patch('/{id}', [StudentController::class, 'update']);
});

Route::prefix('courses')
    ->name('courses.')
    ->middleware('auth:api')
    ->group(function () {
    Route::get('/', [CourseController::class, 'index']);
    Route::post('/', [CourseController::class, 'store']);
    Route::get('/{id}', [CourseController::class, 'show']);
    Route::delete('/{id}', [CourseController::class, 'destroy']);
    Route::patch('/{id}', [CourseController::class, 'update']);

    //Student
    Route::post('/{id}/students', [CourseController::class, 'enrollStudent']);
    Route::delete('/{courseId}/students/{studentId}', [CourseController::class, 'destroyEnrollStudent']);
    Route::get('/{id}/students', [CourseController::class, 'getEnrolledStudents']);

    //Professor
    Route::post('/{id}/professor', [CourseController::class, 'enrollProfessor']);
    Route::delete('/{id}/professor', [CourseController::class, 'destroyEnrollProfessor']);
});

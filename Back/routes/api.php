<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\StudentController; //nose si borrar esto. preguntar
use App\Http\Controllers\Api\AspiranteController;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/super-admin/administration', [StudentController::class, 'index']);
    Route::get('/students/{id}', [StudentController::class, 'indexById']);
    Route::post('/students/profile-photo', [StudentController::class, 'updateProfilePhoto']);
    Route::patch('/students/{id}', [StudentController::class, 'updateApprovalStatus']);
    Route::delete('/students/delete/{id}', [StudentController::class, 'destroy']);
});

Route::post('/signup/super-admin', [AuthController::class, 'signupSuperAdmin']);
Route::post('/signup', [AuthController::class, 'signup']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/password/forgot', [AuthController::class, 'forgotPassword']);
Route::post('/password/reset', [AuthController::class, 'resetPassword']);
Route::get('/verify-token/{token}', [AuthController::class, 'verifyResetToken']);

Route::get('/aspirantes', [AspiranteController::class, 'mostrarAspirantes']);
Route::get('/aspirantes/{id}', [AspiranteController::class, 'mostrarAspiranteIndividual']);
Route::post('/aspirantes', [AspiranteController::class, 'altaAspirante']);
Route::put('/aspirantes/{id}', [AspiranteController::class, 'modificarAspirante']);
Route::delete('/aspirantes/{id}', [AspiranteController::class, 'bajaAspirante']);

Route::get('auth/google', function () {
    return Socialite::driver('google')->redirect();
});

Route::get('auth/google/callback', function () {
    $user = Socialite::driver('google')->user();
});

Route::post('/auth/google/callback', [AuthController::class, 'googleCallback']);
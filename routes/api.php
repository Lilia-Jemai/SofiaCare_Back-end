<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\CommentController;
use App\Http\Controllers\api\DossierController;
use App\Http\Controllers\api\LikeController;
use App\Http\Controllers\api\MedicController;
use App\Http\Controllers\api\PostController;
use App\Http\Controllers\api\QuestionController;
use App\Http\Controllers\api\RDVController;
use App\Http\Controllers\api\RendvousController;
use App\Http\Controllers\api\ResponseController;
use App\Http\Controllers\api\SpecialiteController;
use App\Http\Controllers\PatientController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {

    Route::resource('rdv', RDVController::class);
    Route::post('/auth/logout', [AuthController::class, 'Logout']);
});
Route::get('users', [AuthController::class, 'Index']);

Route::resource('/post', PostController::class);
Route::get('/patient/user/{user_id}', [PatientController::class, 'findByUserId']);
Route::resource('/patient', PatientController::class);

Route::resource('/dossier', DossierController::class);
Route::resource('/rendvous', RendvousController::class);

Route::resource('/comment', CommentController::class);
Route::resource('/like', LikeController::class);
Route::resource('/medic', MedicController::class);
Route::resource('/question', QuestionController::class);
Route::resource('/response', ResponseController::class);
Route::resource('/specialite', SpecialiteController::class);

Route::post('/auth/register', [AuthController::class, 'Register']);
Route::post('/auth/login', [AuthController::class, 'Login']);
Route::get('/user', [AuthController::class, 'Index']);
Route::get('/connected', [AuthController::class, 'user']);

Route::post('/auth/verify', [AuthController::class, 'Validate_Mail']);
Route::post('/auth/verify_code', [AuthController::class, 'Verif_code']);
Route::post('/auth/update_pass', [AuthController::class, 'Update_password']);

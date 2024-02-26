<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::get('/greeting', function () {
    return 'Hello World';
})->middleware('check.user_id');

Route::post('/signin', [UserController::class, 'signInAction'])->name('signIn');
Route::post('/signup', [UserController::class, 'signUpAction'])->name('signUp');

Route::post('/getid', [UserController::class, 'returnUserIdAction'])->name('returnUserId');
Route::post('/getdata', [UserController::class, 'returnUserDataAction'])->name('returnUserData');

Route::put('/changename', [UserController::class, 'changeUserNameAction'])->name('changeUserName');
Route::put('/changepassword', [UserController::class, 'changeUserPassAction'])->name('changeUserPass');
Route::put('/restorepass', [UserController::class, 'restoreUserPassAction'])->name('restorePass');

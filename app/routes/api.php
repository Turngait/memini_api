<?php

use App\Http\Controllers\ActivitiesController;
use App\Http\Controllers\CategoriesController;
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
// Route::get('/greeting', function () {
//     return 'Hello World';
// })->middleware('check.user_id');

Route::prefix('user')->group(function () {
    Route::post('/signin', [UserController::class, 'signInAction'])->name('signIn');
    Route::post('/signup', [UserController::class, 'signUpAction'])->name('signUp');
    
    Route::get('/getid', [UserController::class, 'returnUserIdAction'])->name('returnUserId');
    Route::get('/getdata', [UserController::class, 'returnUserDataAction'])->name('returnUserData');
    
    Route::put('/changename', [UserController::class, 'changeUserNameAction'])->name('changeUserName');
    Route::put('/changepassword', [UserController::class, 'changeUserPassAction'])->name('changeUserPass');
    Route::put('/restorepass', [UserController::class, 'restoreUserPassAction'])->name('restorePass');
});

Route::prefix('activities')->group(function () {
    Route::get('/', [ActivitiesController::class, 'getAllActivities'])->name('getAllActivities');
    Route::post('/', [ActivitiesController::class, 'addActivity'])->name('addActivity');
    Route::patch('/', [ActivitiesController::class, 'editActivity'])->name('editActivity');
    Route::delete('/', [ActivitiesController::class, 'deleteActivity'])->name('deleteActivity');
})->middleware('check.user_id');

Route::prefix('categories')->group(function () {
    Route::get('/', [CategoriesController::class, 'getAllCategories'])->name('getAllCategories');
    Route::post('/', [CategoriesController::class, 'addCategory'])->name('addCategory');
    Route::patch('/', [CategoriesController::class, 'editCategory'])->name('editCategory');
    Route::delete('/', [CategoriesController::class, 'deleteCategory'])->name('deleteCategory');
})->middleware('check.user_id');
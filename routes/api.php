<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\ProgressController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Category;
use App\Models\Dhikr;

Route::prefix('v1')->group(function() {
    Route::get('/categories', function() {
        return Category::orderBy('order')->get();
    });

    Route::get('/categories/{category}/dhikrs', function(Category $category) {
        return $category->dhikrs()->orderBy('order')->get();
    });

    Route::get('/dhikrs-bundle', function () {
        return Category::with(['dhikrs' => function ($query) {
            $query->orderBy('order');
        }])->orderBy('order')->get();
    });

    //--------------------------------------------------------------------------
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1'); //5 login attempts per minute

    Route::middleware('auth:sanctum')->group(function() {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::put('/profile',[AuthController::class, 'updateProfile']);
        Route::put('/profile/password', [AuthController::class, 'changePassword']);
        Route::delete('/account', [AuthController::class, 'deleteAccount']);

        //--------------------------------------------------------------------------
        Route::get('/favorites', [FavoriteController::class, 'index']);
        Route::post('/favorites/{dhikr}', [FavoriteController::class, 'store']);
        Route::delete('/favorites/{dhikr}', [FavoriteController::class, 'destroy']);

        //--------------------------------------------------------------------------
        Route::put('/dhikrs/{dhikr}/progress', [ProgressController::class, 'update']);
        Route::get('/progress/today', [ProgressController::class, 'today']);
    });

    // Google OAuth
    Route::get('/auth/google/redirect', [AuthController::class, 'redirectToGoogle']);
    Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);
    Route::post('/auth/google/token', [AuthController::class, 'loginWithGoogleToken']);
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

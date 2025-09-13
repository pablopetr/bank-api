<?php

use App\Http\Controllers\OrganizationUser\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/organization-user', RegisterController::class)->name('organization-user.register');

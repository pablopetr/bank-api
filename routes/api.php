<?php

use App\Http\Controllers\GetProfileInformationController;
use App\Http\Controllers\IndividualUser\LoginController as IndividualLoginController;
use App\Http\Controllers\IndividualUser\RegisterController as IndividualRegisterController;
use App\Http\Controllers\OrganizationUser\LoginController as OrganizationLoginController;
use App\Http\Controllers\OrganizationUser\RegisterController as OrganizationRegisterController;
use App\Http\Controllers\Transfers\StoreTransferController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/user', GetProfileInformationController::class)->middleware('auth:sanctum');

Route::post('/organization-user', OrganizationRegisterController::class)->name('organization-user.register');
Route::post('/organization-user/login', OrganizationLoginController::class)->name('organization-user.login');

Route::post('/individual-user', IndividualRegisterController::class)->name('individual-user.register');
Route::post('/individual-user/login', IndividualLoginController::class)->name('individual-user.login');

Route::post('/transfers', StoreTransferController::class)->name('transfers.store');

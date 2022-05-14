<?php

use Dealskoo\Deal\Http\Controllers\Admin\DealController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'admin_locale'])->prefix(config('admin.route.prefix'))->name('admin.')->group(function () {

    Route::middleware(['guest:admin'])->group(function () {

    });

    Route::middleware(['auth:admin', 'admin_active'])->group(function () {
        Route::resource('deals', DealController::class)->except(['create', 'store', 'destroy']);
    });

});

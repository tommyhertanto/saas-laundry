<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::get("/", function () {
    return redirect("/dashboard");
});

Route::middleware("auth")->group(function () {
    Route::get("/dashboard", [OrderController::class, "index"])->name(
        "dashboard",
    );
    Route::get("/profile", [ProfileController::class, "edit"])->name(
        "profile.edit",
    );
    Route::patch("/profile", [ProfileController::class, "update"])->name(
        "profile.update",
    );
    Route::delete("/profile", [ProfileController::class, "destroy"])->name(
        "profile.destroy",
    );
    Route::post("/orders/{id}/done", [OrderController::class, "done"])->name(
        "orders.done",
    );

    Route::post("/store", [OrderController::class, "store"])->name(
        "orders.store",
    );
});

require __DIR__ . "/auth.php";

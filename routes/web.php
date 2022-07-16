<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InventoryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('dashboard');
});

Route::get('pos',[InventoryController::class,'create'])->name('pos.create');
Route::get('findPrice',[InventoryController::class,'findPrice'])->name('findPrice');
Route::get('findPos',[InventoryController::class,'find'])->name('findPos');
Route::post('pos/store',[InventoryController::class,'store'])->name('pos.store');
Route::post('pos/update',[InventoryController::class,'update'])->name('pos.update');

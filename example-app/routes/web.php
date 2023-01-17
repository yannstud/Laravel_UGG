<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BinanceController;
use App\Http\Controllers\RiotController;
use Illuminate\Support\Facades\Route;

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
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// binance API
Route::match(['get', 'post'],'/binance', [
    BinanceController::class, 'getData'
])->middleware(['auth', 'verified'])->name('binance');
    
Route::post('/binance/chart', [
    BinanceController::class, 'showSymbol'
])->middleware(['auth', 'verified'])->name('showsymbol');


// Riot API
Route::match(['get', 'post'],'/riot', [
    RiotController::class, 'search'
])->middleware(['auth', 'verified'])->name('riot');

Route::get('/sumonner', [RiotController::class, 'getData'])->name('riotApi.sumonner');
Route::get('/bravery', [RiotController::class, 'bravery'])->name('riotApi.bravery');
Route::post('/update', [RiotController::class, 'update'])->name('riotApi.update');
Route::get('/tierlist', [RiotController::class, 'tierlist'])->name('riotApi.tierlist');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::redirect('/binance', '/binance/chart');
});

require __DIR__.'/auth.php';

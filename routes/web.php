<?php

use App\Http\Livewire\Coins;
use App\Http\Livewire\Roles;
use App\Http\Livewire\Sales;
use App\Http\Livewire\Users;
use App\Http\Livewire\Asignar;
use App\Http\Livewire\Cashout;
use App\Http\Livewire\Reports;
use App\Http\Livewire\Permisos;
use App\Http\Livewire\Products;
use App\Http\Livewire\Categories;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExportController;


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
    return view('welcome');
});
Auth::routes();
  

    
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('login');



Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
  Route::middleware(['auth'])->group(function (){
Route::get('categories', Categories::class);
Route::get('products', Products::class);
Route::get('coins', Coins::class);


Route::get('sales', Sales::class);
Route::get('roles', Roles::class);
Route::get('permisos', Permisos::class);
Route::get('asignar', Asignar::class);
  

Route::get('users', Users::class);
Route::get('cashout', Cashout::class);
Route::get('reports', Reports::class);

});

Route::get('report/pdf/{user}/{type}/{f1}/{f2}',[ExportController::class,'reportPDF']);
Route::get('report/pdf/{user}/{type}',[ExportController::class,'reportPDF']);


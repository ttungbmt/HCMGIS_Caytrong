<?php

use App\Imports\NonghoImport;
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
    return redirect(config('nova.path'). '/nova-map');
//    return view('welcome');
});

Route::get('/test', function (){
//    Excel::import(new NonghoImport, 'imports/giaolong.xlsx');
//    Excel::import(new NonghoImport, 'imports/tanphu.xlsx');
//    Excel::import(new NonghoImport, 'imports/vinhbinh.xlsx');
//    Excel::import(new NonghoImport, 'imports/thanhphong.xlsx');
   return [];
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');



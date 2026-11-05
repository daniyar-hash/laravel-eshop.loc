<?php

use Illuminate\Support\Facades\Route;
use PHPUnit\Metadata\Group;

// Route::get('/', function () {
//     return view('welcome');
// });


Route::livewire('/', 'pages::main.home')->name('home');
Route::livewire('/category/{slug}', 'pages::product.category')->name('category');
Route::livewire('/product/{slug}', 'pages::product.product')->name('product');

Route::middleware('guest')->group(function(){
    Route::livewire('/login', 'pages::user.login')->name('login');
    Route::livewire('/register', 'pages::user.register')->name('register');

});

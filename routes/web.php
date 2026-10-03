<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });


Route::livewire('/', 'pages::main.home')->name('home');
Route::livewire('/category', 'pages::product.category')->name('category');
Route::livewire('/product', 'pages::product.product')->name('product');
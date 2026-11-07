<?php

use Illuminate\Support\Facades\Route;
use PHPUnit\Metadata\Group;

// Route::get('/', function () {
//     return view('welcome');
// });


Route::livewire('/', 'pages::main.home')->name('home');
Route::livewire('/category/{slug}', 'pages::product.category')->name('category');
Route::livewire('/product/{slug}', 'pages::product.product')->name('product');
Route::livewire('/cart', 'pages::user.cart')->name('cart');
Route::livewire('/checkout', 'pages::user.checkout')->name('checkout');

Route::middleware('guest')->group(function(){
    Route::livewire('/login', 'pages::user.login')->name('login');
    Route::livewire('/register', 'pages::user.register')->name('register');

});

Route::middleware('auth')->group(function(){
    Route::get('/logout', function(){
        auth()->logout();
        return redirect()->route('login');
    })->name('logout');
});

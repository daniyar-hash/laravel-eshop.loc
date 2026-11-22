<?php

use Illuminate\Support\Facades\Route;
use PHPUnit\Metadata\Group;
use App\Http\Middleware\AdminMiddleware;


// Route::get('/', function () {
//     return view('welcome');
// });


Route::livewire('/', 'pages::main.home')->name('home');
Route::livewire('/category/{slug}', 'pages::product.category')->name('category');
Route::livewire('/product/{slug}', 'pages::product.product')->name('product');
Route::livewire('/cart', 'pages::user.cart')->name('cart');
Route::livewire('/checkout', 'pages::user.checkout')->name('checkout');
Route::livewire('/search', 'pages::search.search-component')->name('search');

Route::middleware('guest')->group(function(){
    Route::livewire('/login', 'pages::user.login')->name('login');
    Route::livewire('/register', 'pages::user.register')->name('register');

});

Route::middleware('auth')->group(function(){
    Route::get('/logout', function(){
        auth()->logout();
        return redirect()->route('login');
    })->name('logout');

    Route::livewire('/account', 'pages::user.account-component')->name('account');
    Route::livewire('/change-account', 'pages::user.change-account-component')->name('change-account');
    Route::livewire('/orders', 'pages::user.orders-component')->name('orders');
    Route::livewire('/order-show/{id}', 'pages::user.order-show-component')->name('order-show');
});


Route::prefix('admin')
->middleware('admin')
->name('admin.')
->group(function(){

    Route::livewire('/', 'pages::admin.home-component')->name('home');
    Route::livewire('/categories', 'pages::admin.category.category-index-component')
    ->name('categories.index');
     Route::livewire('/categories/create', 'pages::admin.category.category-create-component')
     ->name('categories.create');

     Route::livewire('/categories/{category}/edit', 'pages::admin.category.category-edit-component')
    ->name('categories.edit');

    

});

<?php

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
    return view('welcome');
});

Auth::routes();





Route::group(['middleware'=>'auth'],function(){
	Route::get('/home', 'HomeController@index')->name('home');
    Route::namespace('Billing')->group( function(){
        Route::get('/billing','BillingController@index')->name('billing');
    });
    Route::namespace('Checkout')->group( function(){
        Route::get('/checkout/{plan_id}','CheckoutController@index')->name('checkout');
        Route::post('/process','CheckoutController@process')->name('checkout.process');
    });

    
});
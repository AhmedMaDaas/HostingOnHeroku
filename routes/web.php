<?php

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
    return redirect('/home');
});

Route::get('/home','index@home')->name('home.get');
Route::post('/home','index@postHome')->name('home.post');

Route::get('/all/{productsType}','subIndex@showProducts')->name('getShowAll');
Route::post('/all/{productsType}','subIndex@postShowAll')->name('postShowAll');

Route::get('/login','log@log')->name('login');
Route::post('/login','log@postLog')->name('login.post');

Route::get('/register', 'log@Reg')->name('reg');
Route::post('/register', 'log@postReg')->name('reg.post');

Route::get('/logout','log@logout')->name('logout');

Route::get('/store/{mallId}/{departmentId}','store@showStore')->name('store.get');
Route::post('/store/{mallId}','store@postStore')->name('store.post');

Route::get('/store-brand/{mallId}/{departmentId}','store@showStoreBrand')->name('storebrand.get');
Route::post('/store-brand/{mallId}','store@postStoreBrand');

Route::get('/product/{productId}','product@showProductPage')->name('product.get');
Route::post('/product/{productId}','product@postProductPage');

Route::get('/checkout','bill@showCheckPage')->name('check.get');
Route::post('/checkout','bill@postCheckPage')->name('check.post');

Route::get('/success/{status}','bill@makePayment');

Route::get('/about-us','aboutUs@showPage');

Route::get('/callback/{service}', 'log@callback');

Route::get('/stores-by-department/{departmentId}','store@showStoresByDep')->name('getStoreByDepartment');
Route::post('/stores-by-department/{departmentId}','store@postStoresByDep')->name('postStoreByDepartment');

// Route::get('/test', function(){
//     $deps = \DB::table('departments')
//                 ->select(
//                     'departments.*'
//                 )
//                 ->whereNotExists( function ($query) {
//                     $query->select(DB::raw(1))
//                     ->from('products')
//                     ->whereRaw('departments.id = products.department_id');
//                 })
//                 ->get();
//     return $deps;
// });

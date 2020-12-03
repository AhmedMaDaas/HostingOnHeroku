<?php

Route::group(['prefix'=>'mall-manager', 'namespace'=>'MallManager'], function(){

	Route::get('login', 'ManagerAuth@login');

	Route::post('login', 'ManagerAuth@doLogin');

	Route::get('resset/password', 'ManagerAuth@ressetPassword');

	Route::post('resset/password', 'ManagerAuth@ressetPasswordPost');

	Route::get('resset/password/{token}', 'ManagerAuth@ressetPasswordToken');

	Route::post('resset/password/{token}', 'ManagerAuth@ressetPasswordTokenPost');

	Route::group(['middleware'=>'manager'], function(){

		Route::get('/home', 'ShippingOrders@home');

		Route::get('sales', 'ShippingOrders@mallsSales')->middleware('checkMalls');

		Route::get('sales/mall-sales/{id}', 'ShippingOrders@mallSales')->middleware('checkMalls');

		Route::resource('colors', 'ColorsController');

		Route::resource('departments', 'DepartmentsController');

		Route::delete('colors/destroy/all', 'ColorsController@multiDelete');

		Route::resource('sizes', 'SizesController');

		Route::delete('sizes/destroy/all', 'SizesController@multiDelete');

		Route::resource('weights', 'WeightsController');

		Route::delete('weights/destroy/all', 'WeightsController@multiDelete');

		Route::resource('tradeMarks', 'TradeMarksController');

		Route::delete('tradeMarks/destroy/all', 'TradeMarksController@multiDelete');

		Route::resource('manufacturers', 'ManufacturersController');

		Route::delete('manufacturers/destroy/all', 'ManufacturersController@multiDelete');

		Route::resource('products', 'ProductsController');

		Route::post('products/copy/{id}', 'ProductsController@copyProduct');

		Route::delete('products/destroy/all', 'ProductsController@multiDelete');

		Route::post('upload/image/{pid}', 'ProductsController@uploadFiles');

		Route::post('product/delete-file', 'ProductsController@deleteFile');

		Route::post('upload/main-image/{pid}', 'ProductsController@uploadPhoto');

		Route::post('product/delete-photo', 'ProductsController@deletePhoto');

		Route::post('product/load/shipp-info', 'ProductsController@getShippInfo');

		Route::resource('adds', 'AddsController');

		Route::post('adds/get-data', 'AddsController@getData');

		Route::delete('adds/destroy/all', 'AddsController@multiDelete');

		Route::get('/', function(){
			return redirect('mall-manager/home');
		});

		Route::any('logout', 'ManagerAuth@logout');

		Route::get('language/{lang}', function($lang){
			if(session()->has('lang')){
				session()->forget('lang');
			}
			session()->put('lang', $lang);

			return back();
		});

	});

});
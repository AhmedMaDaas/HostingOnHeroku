<?php

Route::group(['prefix'=>'admin', 'namespace'=>'Admin'], function(){

	Route::get('login', 'AdminAuth@login');

	Route::post('login', 'AdminAuth@doLogin');

	Route::get('resset/password', 'AdminAuth@ressetPassword');

	Route::post('resset/password', 'AdminAuth@ressetPasswordPost');

	Route::get('resset/password/{token}', 'AdminAuth@ressetPasswordToken');

	Route::post('resset/password/{token}', 'AdminAuth@ressetPasswordTokenPost');

	Route::group(['middleware'=>'admin:admin'], function(){

		Route::resource('admins', 'AdminController');

		Route::delete('admins/destroy/all', 'AdminController@multiDelete');

		Route::resource('countries', 'CountriesController');

		Route::delete('countries/destroy/all', 'CountriesController@multiDelete');

		Route::resource('users', 'UsersController');

		Route::delete('users/destroy/all', 'UsersController@multiDelete');

		Route::resource('cities', 'CitiesController');

		Route::delete('cities/destroy/all', 'CitiesController@multiDelete');

		Route::resource('states', 'StatesController');

		Route::delete('states/destroy/all', 'StatesController@multiDelete');

		Route::resource('tradeMarks', 'TradeMarksController');

		Route::delete('tradeMarks/destroy/all', 'TradeMarksController@multiDelete');

		Route::resource('departments', 'DepartmentsController');

		Route::resource('manufacturers', 'ManufacturersController');

		Route::delete('manufacturers/destroy/all', 'ManufacturersController@multiDelete');

		Route::resource('shippings', 'ShippingsController');

		Route::delete('shippings/destroy/all', 'ShippingsController@multiDelete');

		Route::get('shipping/shipping-overview', 'ShippingOrders@allOrders');

		Route::get('shipping/shipping-orders', 'ShippingOrders@pendingOrders');

		Route::post('shipping/delete-order', 'ShippingOrders@deleteOrder');

		Route::post('shipping/accept-order', 'ShippingOrders@acceptOrder');

		Route::post('shipping/reject-order', 'ShippingOrders@rejectOrder');

		Route::post('shipping/return-order', 'ShippingOrders@returnOrder');

		Route::get('malls/sales', 'MallsController@mallsSales');

		Route::get('malls/mall-sales/{id}', 'MallsController@mallSales');

		Route::resource('malls', 'MallsController');

		Route::delete('malls/destroy/all', 'MallsController@multiDelete');

		Route::resource('colors', 'ColorsController');

		Route::delete('colors/destroy/all', 'ColorsController@multiDelete');

		Route::resource('sizes', 'SizesController');

		Route::delete('sizes/destroy/all', 'SizesController@multiDelete');

		Route::resource('weights', 'WeightsController');

		Route::delete('weights/destroy/all', 'WeightsController@multiDelete');

		Route::resource('products', 'ProductsController');

		Route::post('products/copy/{id}', 'ProductsController@copyProduct');

		Route::delete('products/destroy/all', 'ProductsController@multiDelete');

		Route::post('upload/image/{pid}', 'ProductsController@uploadFiles');

		Route::post('product/delete-file', 'ProductsController@deleteFile');

		Route::post('upload/main-image/{pid}', 'ProductsController@uploadPhoto');

		Route::post('product/delete-photo', 'ProductsController@deletePhoto');

		Route::post('product/load/shipp-info', 'ProductsController@getShippInfo');

		Route::resource('adds', 'AddsController');

		Route::delete('adds/destroy/all', 'AddsController@multiDelete');

		Route::post('adds/get-data', 'AddsController@getData');

		Route::get('settings', 'Settings@settings');

		Route::post('settings', 'Settings@save');

		Route::get('website-info', 'WebSiteInfoController@edit');

		Route::post('website-info/{id}', 'WebSiteInfoController@save');

		Route::post('add-attr-info', 'WebSiteInfoController@addAttrInfo');

		Route::post('delete-attr-info', 'WebSiteInfoController@deleteAttrInfo');

		Route::get('/home', 'ShippingOrders@home');

		Route::post('new-notifications', 'NotificationsController@getNew');

		Route::post('make-notifications-old', 'NotificationsController@makeOld');

		Route::get('/', function(){
			return redirect('admin/home');
		});

		Route::any('logout', 'AdminAuth@logout');

		Route::get('language/{lang}', function($lang){
			if(session()->has('lang')){
				session()->forget('lang');
			}
			session()->put('lang', $lang);

			return back();
		});

	});

});
<?php

Route::group(['prefix'=>'shipping', 'namespace'=>'Shipping'], function(){

	Route::get('login', 'ShippingAuth@login');

	Route::post('login', 'ShippingAuth@doLogin');

	Route::get('resset/password', 'ShippingAuth@ressetPassword');

	Route::post('resset/password', 'ShippingAuth@ressetPasswordPost');

	Route::get('resset/password/{token}', 'ShippingAuth@ressetPasswordToken');

	Route::post('resset/password/{token}', 'ShippingAuth@ressetPasswordTokenPost');

	Route::group(['middleware' => 'shipping'], function(){

		Route::get('shipping-overview', 'ShippingOrders@allOrders');

		Route::get('shipping-orders', 'ShippingOrders@pendingOrders');

		Route::post('delete-order', 'ShippingOrders@deleteOrder');

		Route::post('accept-order', 'ShippingOrders@acceptOrder');

		Route::post('update-order', 'ShippingOrders@updateOrder');

		Route::post('reject-order', 'ShippingOrders@rejectOrder');

		Route::post('return-order', 'ShippingOrders@returnOrder');

		Route::post('make-old', 'ShippingOrders@makeOld');

		Route::post('new-notifications', 'NotificationsController@getNew');

		Route::post('make-notifications-old', 'NotificationsController@makeOld');

		Route::get('/home', 'ShippingOrders@home');

		Route::get('/', function(){
			return redirect('shipping/home');
		});

		Route::any('logout', 'ShippingAuth@logout');

		Route::get('language/{lang}', function($lang){
			if(session()->has('lang')){
				session()->forget('lang');
			}
			session()->put('lang', $lang);

			return back();
		});

	});

});
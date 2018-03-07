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
    return view('welcome');
});

Route::get('index',[
	'as'=>'trang-chu', 'uses'=>'PageController@getIndex'
]);

Route::get('loai-san-pham/{type}',[
'as'=>'loaisanpham',
'uses'=>'PageController@getLoaiSp'
]);

Route::get('chi-tiet-san-pham/{id}',[
	'as'=>'chitietsanpham',
	'uses'=>'PageController@getChitiet'
]);

Route::get('lien-he',[
	'as'=>'lienhe',
	'uses'=>'PageController@getLienHe'
]);

Route::get('about',[
	'as'=>'about',
	'uses'=>'PageController@getAbout'
]);


Route::get('add-to-cart/{id}',[
	'as' => 'themgiohang',
	'uses' => 'PageController@getAddToCart'
]);


Route::get('del-cart/{id}',[
	'as' => 'xoagiohang',
	'uses' => 'PageController@getDetailItemCart'
]);


Route::get('dat-hang',[
	'as' =>'dathang',
	'uses' => 'PageController@getCheckout'
]);

Route::post('dat-hang',[
	'as' => 'dathang',
	'uses' => 'PageController@postCheckout'
]);

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
    return view('auth/login');
});
Auth::routes();
Route::get('localeLang', 'NavController@localeLang');
Route::get('/home', 'HomeController@index')->name('home');
Route::post('uploadCog', 'UploadController@uploadCog');
Route::post('showCog', 'UploadController@showCog');
Route::post('deleteCog', 'UploadController@deleteCog');
Route::get('getQatSubnet', 'SubnetController@getSubnet');
Route::get('getQatTemplate', 'TemplateController@getTemplate');
Route::get('getQatLoginUser', 'TemplateController@getQatLoginUser');
Route::get('getQatCity', 'CityController@getCity');
Route::get('getQatTime', 'TimeController@getTime');
Route::get('getQatLocation', 'LocationController@getLocation');
Route::get('getQatData', 'DataController@getQatData');
Route::get('getQatTemplateData', 'TemplateController@getQatTemplateData');
Route::get('insertQatTemplateName', 'TemplateController@insertQatTemplateName');
Route::get('removeQatTemplateName', 'TemplateController@removeQatTemplateName');
Route::get('loadQatElementData', 'TemplateController@loadQatElementData');
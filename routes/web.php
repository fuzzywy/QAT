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

Route::get('getQatCity', 'CityController@getCity');
Route::get('getQatTime', 'TimeController@getTime');
Route::get('getQatLocation', 'LocationController@getLocation');
Route::post('getQatData', 'DataController@getQatData');

// [TemplateManage routes]
Route::prefix('template')->namespace('TemplateManage')->group(function () {
	Route::post('getQatTemplate', 'TemplateController@getTemplate');
	Route::post('getQatLoginUser', 'TemplateController@getQatLoginUser');
	Route::post('getQatTemplateData', 'TemplateController@getQatTemplateData');
	Route::post('insertQatTemplateName', 'TemplateController@insertQatTemplateName');
	Route::post('removeQatTemplateName', 'TemplateController@removeQatTemplateName');
	Route::post('loadQatElementData', 'TemplateController@loadQatElementData');
	Route::post('orderQatElementData', 'TemplateController@orderQatElementData');
	Route::post('loadQatFormulaData', 'TemplateController@loadQatFormulaData');
	Route::post('selectKpiFormula', 'TemplateController@selectKpiFormula');
	Route::post('addQatFormula', 'TemplateController@addQatFormula');
	Route::post('deleteQatFormula', 'TemplateController@deleteQatFormula');
	Route::post('modifyQatFormula', 'TemplateController@modifyQatFormula');
	Route::post('insertQatElement', 'TemplateController@insertQatElement');
	Route::post('deleteQatElement', 'TemplateController@deleteQatElement');
	Route::post('addQatTemplate', 'TemplateController@addQatTemplate');
});

// [ReportManage routes]
Route::prefix('report')->namespace('ReportManage')->group(function () {
    Route::post('add', 'ReportController@add');
    Route::post('del', 'ReportController@del');
    Route::post('mod', 'ReportController@mod');
    Route::post('run', 'ReportController@run');
    Route::post('stop', 'ReportController@stop');
    Route::get('list', 'ReportController@list');
    Route::get('list', 'ReportController@list');
});
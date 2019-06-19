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
// [cog routes]
Route::post('uploadCog', 'UploadController@upload');
Route::post('showCog', 'UploadController@query');
Route::post('deleteCog', 'UploadController@delete');

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
/*Route::prefix('report')->namespace('ReportManage')->group(function () {
    Route::post('add', 'ReportController@add');
    Route::post('del', 'ReportController@del');
    Route::post('mod', 'ReportController@mod');
    Route::post('run', 'ReportController@run');
    Route::post('stop', 'ReportController@stop');
    Route::get('list', 'ReportController@list');
    Route::get('list', 'ReportController@list');
});*/

// [kgetManage routes]
Route::prefix('kget')->namespace('KgetManage')->group(function () {
	Route::get('getQatKgetMoData', 'KgetController@getQatKgetMo');
	Route::post('getMoByParamFilterData', 'KgetController@getMoByParamFilter');
	Route::get('getKgetTaskData', 'KgetController@getKgetTask');
	Route::post('getKgetParamData', 'KgetController@getKgetParam');
	Route::post('getKgetData', 'KgetController@getKgetData');
	Route::post('exportKgetFile', 'KgetController@exportKgetFile');
	Route::post('insertKgetCrontabTask', 'KgetController@insertKgetCrontabTask');
	Route::post('getKgetCrontabTask', 'KgetController@getKgetCrontabTask');
	Route::post('checkKgetTaskName', 'KgetController@checkKgetTaskName');
	Route::post('deleteKgetCrontabTask', 'KgetController@deleteKgetCrontabTask');
	Route::get('getQatKgetMoListData', 'KgetController@getQatKgetMoList');

});

// [kgetManage routes]
Route::prefix('paramCheck')->namespace('KgetManage')->group(function () {
	Route::get('getQatParamItemData', 'ConsistencyController@getItem');
	Route::post('getQatParamOperatorData', 'ConsistencyController@getOperator');
	Route::get('getQatParamProvinceData', 'ConsistencyController@getProvince');
	Route::post('getQatParamCityData', 'ConsistencyController@getCity');
	Route::post('getQatParamData', 'ConsistencyController@getParam');
	Route::post('exportQatParamData', 'ConsistencyController@exportParam');
	Route::post('getQatParamDetailData', 'ConsistencyController@getParamDetail');
	Route::post('exportQatParamDetailData', 'ConsistencyController@exportParamDetail');
	Route::post('uploadWhiteList', 'ConsistencyController@uploadWhiteList');
	Route::post('exportWhiteList', 'ConsistencyController@exportWhiteList');
});

// [TaskManage routes]
Route::prefix('storage')->namespace('TaskManage')->group(function () {
	Route::get('getStorageType', 'StorageController@getStorageType');
	Route::post('saveTask', 'StorageController@saveTask');
	Route::get('getTask', 'StorageController@getTask');
	Route::post('deleteTask', 'StorageController@deleteTask');
	Route::post('runTask', 'StorageController@runTask');
	Route::post('stopTask', 'StorageController@stopTask');
	Route::post('uploadTaskFile', 'StorageController@uploadFile');
});

// [UserManage routes]
Route::get('/waitReview', function () {
    return view('auth/waitReview');
});

Route::prefix('user')->namespace('UserManage')->group(function () {
	Route::get('getUser', 'UserController@getUser');
	Route::get('showReview', 'UserController@getReviews');
	Route::get('getRoles', 'UserController@getRoles');
	Route::post('reviewUser', 'UserController@reviewUser');
	Route::get('showUser', 'UserController@getUsers');
	Route::post('modifyUser', 'UserController@modifyUser');
	Route::post('deleteUser', 'UserController@deleteUser');
	Route::get('showRole', 'UserController@getRoleData');
	Route::post('modifyRole', 'UserController@modifyRole');
	Route::post('deleteRole', 'UserController@deleteRole');
	Route::post('showPermission', 'UserController@getPermissionData');
});

// [SiteManage routes]
Route::prefix('site')->namespace('SiteManage')->group(function () {
	
	Route::post('loadSiteData', 'SiteController@query');
	Route::post('uploadSiteFile', 'SiteController@upload');
	Route::post('exportSiteData', 'SiteController@export');
	
});
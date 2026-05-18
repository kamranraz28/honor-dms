<?php

use App\Http\Controllers\AiInActionController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ProductController;
use App\Jobs\GenerateRetailerImeiStockReport;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\AiFixController;

use Illuminate\Support\Facades\DB;

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

// Common Route
Route::get('/ses', ['as' => 'ses', 'uses' => 'AuthController@Ses']);
Route::get('/logout', ['as' => 'logout', 'uses' => 'AuthController@Logout']);

Route::get('/report-progress', function () {
    return response()->json(['progress' => Cache::get('retailer_imei_report_progress', 0)]);
})->name('admin.reportProgress');

Route::group(['prefix' => 'ai-fix'], function () {
    Route::get('/', [AiFixController::class, 'index'])->name('ai-fix.index');
    Route::post('/analyze', [AiFixController::class, 'analyze'])->name('ai-fix.analyze');
    Route::post('/approve/{id}', [AiFixController::class, 'approve'])->name('ai-fix.approve');
    Route::post('/decline/{id}', [AiFixController::class, 'decline'])->name('ai-fix.decline');
});


Route::get('/dashboard-cache', function () {
    $keys = [
        'requestRetailerCount',
        'returnCount',
        'totalPrimarySale',
        'monthlyPrimarySale',
        'todayPrimarySale',
        'totalSecondarySale',
        'monthlySecondarySale',
        'todaySecondarySale',
        'totalTertiarySale',
        'monthlyTertiarySale',
        'todayTertiarySale',
        'dayinmonthchartdata',
        'monthinyearchartdata',
        'monthlytopproductchart',
        'monthlytopretailerchart',
        'todaybrandwisesalechart',
        'monthlybrandwisesalechart',
        'monthlytopproductsalechart',
        'monthlytopdistributorchart',
    ];

    foreach ($keys as $key) {
        Cache::forget($key);
    }

    return redirect()->route('admin.dashboard')->with('success', 'Dashboard cache cleared successfully.');
})->name('admin.dashboard.cache');

Route::get('/jobs', 'JobController@index');
// web.php
Route::get('/jobs/data', 'JobController@data');
Route::get('/jobs/{id?}/details', 'JobController@details');


Route::get('/warehouse-panel/jobs', 'JobController@warehouseJobs');
Route::get('/warehouse-panel/jobs/{id?}/details', 'JobController@jobDetails');

// Guest Route
Route::group(['middleware' => 'web'], function () {
    Route::get('/', ['as' => 'guest.home', 'uses' => 'GuestController@HomeView']);
});

//Ajax Searching
Route::get('retailers/search', 'AjaxSearchController@retailerSearch')->name('retailers.search');

Route::get('distributors/search', 'AjaxSearchController@distributorSearch')->name('distributors.search');

Route::get('brands/search', 'AjaxSearchController@brandSearch')->name('brands.search');
Route::get('category/search', 'AjaxSearchController@categorySearch')->name('category.search');
Route::get('products/search', 'AjaxSearchController@productSearch')->name('products.search');
Route::get('divisions/search', 'AjaxSearchController@divisionSearch')->name('divisions.search');
Route::get('districts/search', 'AjaxSearchController@districtSearch')->name('districts.search');
Route::get('upazilas/search', 'AjaxSearchController@upazilaSearch')->name('upazilas.search');

//Auth Route

Route::group(['middleware' => 'web'], function () {
    Route::get('/login1', ['as' => 'login', 'uses' => 'AuthController@LoginView']);
    Route::get('/login', ['as' => 'auth.login', 'uses' => 'AuthController@LoginView']);
    Route::post('/login', ['as' => 'auth.login.store', 'uses' => 'AuthController@LoginViewStore']);

    Route::get('/registration', ['as' => 'auth.registration', 'uses' => 'AuthController@RegistrationView']);
    Route::post('/registration', ['as' => 'auth.registration.store', 'uses' => 'AuthController@RegistrationViewStore']);
});



//==========================forgetPassword==================
Route::post('/forgetPassword', ['as' => 'guest.forgetPassword', 'uses' => 'AuthController@ForgetPassword']);

Route::get('/forgetPasswordLink/{id?}', ['as' => 'guest.forgetPasswordLink', 'uses' => 'AuthController@ForgetPasswordLink']);

Route::post('/forgetPasswordChange', ['as' => 'guest.forgetPasswordChange', 'uses' => 'AuthController@ForgetPasswordChange']);
//==========================forgetPassword==================




// verify area===============

Route::group(['prefix' => 'verify'], function () {


    Route::get('/verifypass/{code}/{uname}/{pass}', ['as' => 'guest.verifypass', 'uses' => 'VerifyController@VerifyPassView'])->where(['code' => '[0-9]+']);
    ;

    Route::get('/verifyProduct/{sno}', ['as' => 'guest.verifyproduct', 'uses' => 'ApiController@verifyProduct'])->where(['code' => '[0-9]+']);


    Route::get('/verifyProducts/{sno?}', ['as' => 'guest.verifySamsungProduct', 'uses' => 'VerifyController@VerifySamsungProductView']);


    Route::post('/verifyProduct', ['as' => 'guest.verifySamsungProduct.store', 'uses' => 'VerifyController@VerifySamsungProductViewStore']);
});

// verify area===============

//Clear Cache facade value:
Route::get('/clear-cache', function () {
    $exitCode = Artisan::call('cache:clear');
    return '<h1>Cache facade value cleared</h1>';
});

//Reoptimized class loader:
Route::get('/optimize', function () {
    $exitCode = Artisan::call('optimize:clear');
    return '<h1>Reoptimized class loader</h1>';
});

Route::get('/run-queue', function () {
    // Check if there are any jobs with 0 attempts
    $pendingJobs = DB::table('jobs')->where('attempts', 0)->count();

    if ($pendingJobs > 0) {
        // Run the queue worker only if there are jobs with 0 attempts
        Artisan::call('queue:work', [
            '--once' => true,
        ]);

        return '<h1>Queue run successful</h1>';
    }

    return '<h1>No pending jobs to run</h1>';
});

Route::get('/restart', function () {

    Artisan::call('queue:restart');

    return '<h1>Queue restart successful</h1>';
});


Route::get('/restart-queue', function () {
    // Dispatch the queue:work command
    Artisan::call('queue:restart');

    return '<h1>Queue restart successful</h1>';
});

Route::get('/config-clear', function () {
    $exitCode = Artisan::call('config:clear');
    return '<h1>Config Clear loader</h1>';
});

//Clear Route cache:
Route::get('/route-clear', function () {
    $exitCode = Artisan::call('route:clear');
    return '<h1>Route cache cleared</h1>';
});

//Clear View cache:
Route::get('/view-clear', function () {
    $exitCode = Artisan::call('view:clear');
    return '<h1>View cache cleared</h1>';
});


//Clear Config cache:
Route::get('/config-cache', function () {
    $exitCode = Artisan::call('config:cache');
    return '<h1>Clear Config cleared</h1>';
});

Route::get('/mira', ['as' => 'admin.mira', 'uses' => 'AdminController@generateReportShow']);
Route::post('/mira', ['as' => 'admin.miraPost', 'uses' => 'ChatGPTReportController@generateReport']);

//Admin Route

Route::group(['prefix' => 'admin-panel', 'middleware' => ['web']], function () {

    Route::get('/ai', 'AiInActionController@index')->name('ai.index');
    Route::post('/ai/execute', 'AiInActionController@execute');
    Route::post('/ai/execute-confirmed', 'AiInActionController@executeConfirmed');
    Route::get('/ai/undo-available', [AiInActionController::class, 'undoAvailable']);
    Route::post('/ai/undo/{logId}', [AiInActionController::class, 'undoLast']);
    Route::get('/ai/history/today', [AiInActionController::class, 'todayHistory']);
    // Route::get('/mira', ['as'=>'admin.mira','uses'=>'AdminController@generateReportShow']);
    // Route::post('/mira', ['as'=>'admin.miraPost','uses'=>'ChatGPTReportController@generateReport']);

    Route::get('/test', ['as' => 'admin.test', 'uses' => 'AdminController@Test']);

    Route::get('/dashboard', ['as' => 'admin.dashboard', 'uses' => 'AdminController@DashboardView']);

    Route::get('/download-report', ['as' => 'admin.downloadReport', 'uses' => 'AdminController@downloadReport']);

    //Report Format Start Here
    Route::group(['prefix' => 'reports'], function () {
        Route::get('/stock-report', ['as' => 'admin.stockReport', 'uses' => 'ReportController@stockReport']);
        Route::post('/allReceiveReport', ['as' => 'admin.allReceiveReport', 'uses' => 'ReportController@stockReportDownload']);
        Route::get('/currentMonthStockReport', ['as' => 'admin.currentMonthReceiveReport', 'uses' => 'ReportController@currentMonthStockReport']);

        Route::get('/imeiStockReport', ['as' => 'admin.retailerImeiStockReport', 'uses' => 'ReportController@imeiStockReport']);
        Route::get('/retailerImeiStockReportDownload', ['as' => 'admin.retailerImeiStockReportDownload', 'uses' => 'ReportController@retailerImeiStockReportDownload']);
        Route::get('/distributorImeiStockReportDownload', ['as' => 'admin.distributorImeiStockReportDownload', 'uses' => 'ReportController@distributorImeiStockReportDownload']);
        Route::get('/download-retailer-stock-report/{jobId}', ['as' => 'admin.downloadRetailerStockReport', 'uses' => 'ReportController@downloadGeneratedStockReport']);

        Route::get('/distributorStockReport', ['as' => 'admin.dailyStockReport', 'uses' => 'ReportController@distributorStockReport']);
        Route::post('/daily-stock-Report', ['as' => 'dailyStockReport.store', 'uses' => 'ReportController@distributorStockReportDownload']);

        Route::get('/tertiarySalesReport', ['as' => 'admin.dailySalesReport', 'uses' => 'ReportController@tertiarySalesReport']);
        Route::post('/tertiarySalesReportDownload', ['as' => 'admin.dailySalesReport.store', 'uses' => 'ReportController@tertiarySalesReportDownload']);
        Route::get('/terExcel', ['as' => 'admin.stock.terexcel', 'uses' => 'ReportController@fullTertiaryReportDownload']);
        Route::get('/currentMonthTerExcel', ['as' => 'currentMonthTerExcel', 'uses' => 'ReportController@currentMonthTertiaryDownload']);
        Route::get('/lastSixMonthTerExcel', ['as' => 'lastSixMonthTerExcel', 'uses' => 'ReportController@lastSixMonthTertiaryDownload']);

        Route::get('/replaceReport', ['as' => 'admin.dailyReplaceReport', 'uses' => 'ReportController@replaceReport']);
        Route::post('/replaceReportDownload', ['as' => 'admin.dailyReplaceReport.store', 'uses' => 'ReportController@replaceReportDownload']);

        Route::get('/primaryTransferReport', ['as' => 'admin.primaryTransferReport', 'uses' => 'ReportController@primaryTransferReport']);
        Route::post('/primaryTransferReport', ['as' => 'admin.primaryTransferReport.store', 'uses' => 'ReportController@primaryTransferReportStore']);

        Route::get('/secondaryTransferReport', ['as' => 'admin.transferReport', 'uses' => 'ReportController@secondaryTransferReport']);
        Route::post('/secondaryTransferReport', ['as' => 'admin.transferReportStore', 'uses' => 'ReportController@secondaryTransferReportStore']);

        Route::get('/primaryAndSecondaryDLReport', ['as' => 'admin.dailyPurchaseSaleReport1', 'uses' => 'ReportController@primaryAndSecondaryDLReport']);
        Route::post('/primaryAndSecondaryDLReport', ['as' => 'admin.dailyPurchaseSaleReport1.store', 'uses' => 'ReportController@primaryAndSecondaryDLReportDownload']);
        Route::get('/priExcel', ['as' => 'admin.stock.pexcel', 'uses' => 'ReportController@fullPrimaryReportDownload']);
        Route::get('/currentMonthPrimary', ['as' => 'currentMonthPrimaryExcel', 'uses' => 'ReportController@currentMonthPrimaryDownload']);
        Route::get('/lastSixMonthPrimary', ['as' => 'lastSixmonthPrimaryExcel', 'uses' => 'ReportController@lastSixmonthPrimaryDownload']);
        Route::get('/secExcel', ['as' => 'admin.stock.sexcel', 'uses' => 'ReportController@fullSecondaryReportDownload']);
        Route::get('/currentMonthSecondary', ['as' => 'currentMonthExcel', 'uses' => 'ReportController@currentMonthSecondaryDownload']);
        Route::get('/lastSixMonthSecondary', ['as' => 'lastSixmonthExcel', 'uses' => 'ReportController@lastSixmonthSecondaryDownload']);

        Route::get('/distributorDetailsStockReport', ['as' => 'admin.dailyDistStockReportV1', 'uses' => 'ReportController@distributorDetailsStockReport']);
        Route::post('/distributorDetailsStockReport', ['as' => 'admin.dailyDistStockReportV1.store', 'uses' => 'ReportController@distributorDetailsStockReportDownload']);

        //================VAT Report======================
        Route::get('/vatReport', ['as' => 'admin.vatReport', 'uses' => 'ReportController@vatReport']);
        Route::post('/vatReportStore', ['as' => 'admin.vatReportStore', 'uses' => 'ReportController@vatReportStore']);
        Route::get('/vatReportDownload', ['as' => 'admin.vatReportDownload', 'uses' => 'ReportController@fullVatReportDownload']);
        Route::get('/currentMonthVatReportDownload', ['as' => 'admin.currentMonthVatReportDownload', 'uses' => 'ReportController@currentMonthVatReportDownload']);

        Route::get('/warehouseStockReport', ['as' => 'admin.warehouseStockReport', 'uses' => 'ReportController@warehouseStockReport']);
        Route::post('/warehouseStockReport', ['as' => 'admin.warehouseStockReportStore', 'uses' => 'ReportController@warehouseStockDownload']);
        Route::get('/warehouseStockDownload', ['as' => 'warehouseStockDownload', 'uses' => 'ReportController@warehouseStockFullDownload']);

        Route::get('/todaysOrder', ['as' => 'admin.todaysOrder', 'uses' => 'ReportController@todaysOrder']);
        Route::post('/todaysOrder', ['as' => 'admin.todaysOrderStore', 'uses' => 'ReportController@todaysOrderStore']);
    });


    Route::group(['prefix' => 'ajax-route'], function () {
        Route::get('/districtSelectBoxOnDivisionWithAjax/{id?}', ['as' => 'admin.districtSelectBoxOnDivisionWithAjax', 'uses' => 'AdminController@DistrictSelectBoxOnDivisionWithAjax'])->where(['id' => '[0-9]+']);

        Route::get('/upazilaSelectBoxOnDistrictWithAjax/{id?}', ['as' => 'admin.upazilaSelectBoxOnDistrictWithAjax', 'uses' => 'AdminController@UpazilaSelectBoxOnDistrictWithAjax'])->where(['id' => '[0-9]+']);


        Route::get('/districtSelectBoxOnRetailerWithAjax/{id?}', ['as' => 'admin.districtSelectBoxOnRetailerWithAjax', 'uses' => 'AdminController@DistrictSelectBoxOnRetailerWithAjax'])->where(['id' => '[0-9]+']);

        Route::get('/districtSelectBoxOnRetailerWithAjax/{id?}', ['as' => 'admin.districtSelectBoxOnRetailerWithAjax', 'uses' => 'AdminController@DistrictSelectBoxOnRetailerWithAjax'])->where(['id' => '[0-9]+']);

        Route::get('/upazilaSelectBoxOnRetailerWithAjax/{id?}', ['as' => 'admin.upazilaSelectBoxOnRetailerWithAjax', 'uses' => 'AdminController@UpazilaSelectBoxOnRetailerWithAjax'])->where(['id' => '[0-9]+']);

        Route::get('/distributorSelectBoxOnRetailerWithAjax/{id?}', ['as' => 'admin.distributorSelectBoxOnRetailerWithAjax', 'uses' => 'AdminController@DistributorSelectBoxOnRetailerWithAjax'])->where(['id' => '[0-9]+']);

        Route::get('/dontworryimeikeyup/{id?}', ['as' => 'admin.dontworryimeikeyup', 'uses' => 'AdminController@Dontworryimeikeyup']);

    });




    // configarations area===============

    Route::group(['prefix' => 'configarations'], function () {

        // Route for setting=========

        Route::get('/setting', ['as' => 'admin.setting', 'uses' => 'AdminController@SettingView']);
        Route::put('/setting', ['as' => 'admin.setting.update', 'uses' => 'AdminController@SettingUpdate']);

        // Route for setting=========

        // Route for retailer=========

        Route::get('/inactiveretailer', ['as' => 'admin.inactiveretailer', 'uses' => 'UserController@InactiveRetailerView']);

        Route::get('/retailerdwnld', ['as' => 'admin.retailerdwnld', 'uses' => 'AdminController@RetailerdwnldView']);
        Route::post('/retailerdwnld', ['as' => 'admin.retailerdwnld.store', 'uses' => 'AdminController@RetailerdwnldViewStore']);

        // Route for retailer=========


        // Route for salesrepresentative=========

        Route::get('/salesrepresentative', ['as' => 'admin.salesrepresentative', 'uses' => 'AdminController@SalesrepresentativeView']);
        Route::post('/salesrepresentative', ['as' => 'admin.salesrepresentative.store', 'uses' => 'AdminController@SalesrepresentativeViewStore']);
        Route::put('/salesrepresentative', ['as' => 'admin.salesrepresentative.update', 'uses' => 'AdminController@SalesrepresentativeUpdate']);
        Route::delete('/salesrepresentative/{id}', ['as' => 'admin.salesrepresentative.delete', 'uses' => 'AdminController@SalesrepresentativeDestroy'])->where(['id' => '[0-9]+']);

        // Route for salesrepresentative=========

        Route::resource('brands', 'BrandController');
        Route::resource('categories', 'CategoryController');
        Route::resource('specifications', 'SpecificationController');

        Route::resource('promotions', 'PromotionController');
        Route::get('/singlepromo/{id}', ['as' => 'admin.singlepromo', 'uses' => 'PromotionController@promotionDetails'])->where(['id' => '[0-9]+']);
        Route::delete('/promo/promodetails/{id}', ['as' => 'admin.promo.promodetails.delete', 'uses' => 'PromotionController@PromoDetailsDestroy'])->where(['id' => '[0-9]+']);
        Route::put('/promo/promodetails', ['as' => 'admin.promo.promodetails.update', 'uses' => 'PromotionController@PromoDetailsUpdate']);
        Route::post('/promo/promodetails/add', ['as' => 'admin.promo.promodetails.add', 'uses' => 'PromotionController@PromoDetailsAdd']);
        Route::post('/promo/changeActiveStatus', ['as' => 'admin.promo.changeActiveStatus', 'uses' => 'PromotionController@changeActiveStatus']);
        Route::post('/promo/changeActiveStatusPromoDetails', ['as' => 'admin.promo.changeActiveStatusPromoDetails', 'uses' => 'PromotionController@ChangeStatusPromoDetails']);

        Route::get('/promort', ['as' => 'admin.promort', 'uses' => 'PromotionController@promortView']);
        Route::post('/promort', ['as' => 'admin.promort.store', 'uses' => 'PromotionController@promortViewStore']);
        Route::get('/singlepromort/{id}', ['as' => 'admin.singlepromort', 'uses' => 'PromotionController@promortDetails'])->where(['id' => '[0-9]+']);
        Route::put('/promort', ['as' => 'admin.promort.update', 'uses' => 'PromotionController@PromortUpdate']);
        Route::delete('/promort/{id}', ['as' => 'admin.promort.delete', 'uses' => 'PromotionController@PromortDestroy'])->where(['id' => '[0-9]+']);
        Route::post('/promort/promortdetails/add', ['as' => 'admin.promort.promortdetails.add', 'uses' => 'PromotionController@promortDetailsAdd']);
        Route::put('/promort/promortdetails', ['as' => 'admin.promort.promortdetails.update', 'uses' => 'PromotionController@promortDetailsUpdate']);
        Route::delete('/promort/promortdetails/{id}', ['as' => 'admin.promort.promortdetails.delete', 'uses' => 'PromotionController@promortDetailsDestroy'])->where(['id' => '[0-9]+']);
        Route::post('/promort/changeActiveStatus', ['as' => 'admin.promort.changeActiveStatus', 'uses' => 'PromotionController@ChangeActiveStatusPromort']);
        Route::post('/promort/changeActiveStatusPromortDetails', ['as' => 'admin.promort.changeActiveStatusPromortDetails', 'uses' => 'PromotionController@changeActiveStatusPromortDetails']);


        Route::resource('promortkeys', 'PromortKeyController');
        Route::post('/promortkey/statusChange', ['as' => 'admin.promortkey.statusChange', 'uses' => 'PromortKeyController@statusChange']);

        // Route for product=========
        Route::resource('products', 'ProductController');
        Route::post('/product/dontworry/active', ['as' => 'admin.product.dontworry.active', 'uses' => 'AdminController@ProductDontWorryActive']);
        Route::post('/product/dontworry/inactive', ['as' => 'admin.product.dontworry.inactive', 'uses' => 'AdminController@ProductDontWorryInactive']);

        // Route for product=========


        // Route for stock=========

        Route::get('/stockExcel', ['as' => 'admin.stock.excel', 'uses' => 'AdminController@StockViewExcel']);

        Route::resource('stocks', 'StockController');
        Route::post('/stockFilter', ['as' => 'stocks.filter', 'uses' => 'StockController@filter']);


        // Route for stock=========


        // Route for promo=========




        // Route for promort=========

        Route::delete('/promort/promortretailer/{id}', ['as' => 'admin.promort.promortretailer.delete', 'uses' => 'AdminController@PromortRetailerDestroy'])->where(['id' => '[0-9]+']);

        Route::post('/promort/retailer/add', ['as' => 'admin.promort.add.retailer', 'uses' => 'AdminController@PromortRetailerAdd']);

        // Route for promort=========


        // Route for upload1=========

        Route::get('/upload1', ['as' => 'admin.upload1', 'uses' => 'AdminController@Upload1View']);
        Route::post('/upload1', ['as' => 'admin.upload1.store', 'uses' => 'AdminController@Upload1ViewStore']);
        //Route::put('/upload1', ['as'=>'admin.upload1.update','uses'=>'AdminController@Upload1Update']);
        //Route::delete('/upload1/{id}', ['as'=>'admin.upload1.delete','uses'=>'AdminController@Upload1Destroy'])->where(['id' => '[0-9]+']);

        // Route for upload1=========

        //Order List starts
        Route::get('/orderList', ['as' => 'admin.orderList', 'uses' => 'OrderController@orderList']);
        Route::get('/order/edit/{id?}', ['as' => 'admin.orderEdit', 'uses' => 'OrderController@orderEdit']);
        Route::post('/order/update/{id?}', ['as' => 'admin.orderUpdate', 'uses' => 'OrderController@orderUpdate']);
        Route::post('/order/delete', ['as' => 'admin.orderDelete', 'uses' => 'OrderController@orderDelete']);
        Route::post('/order/changeStatus', ['as' => 'admin.orderChangeStatus', 'uses' => 'OrderController@orderChangeStatus']);
        Route::post('/orderListSearch', ['as' => 'admin.orderSearch', 'uses' => 'OrderController@orderListSearch']);

        //Order List Ends
        Route::get('/dataSink', ['as' => 'admin.dataSink', 'uses' => 'AdminController@dataSink']);










    });

    // configarations area===============


    // active area===============

    Route::group(['prefix' => 'active'], function () {

        Route::get('/activewarranty', ['as' => 'retailer.activewarranty', 'uses' => 'RetailerController@ActivewarrantyView']);
        Route::post('/activewarranty', ['as' => 'retailer.activewarranty.store', 'uses' => 'RetailerController@ActivewarrantyViewStore']);
    });

    // active area===============



    // Route for user=========

    Route::resource('users', 'UserController');
    Route::put('/updateOfficeid', ['as' => 'admin.user.updateOfficeid', 'uses' => 'UserController@UpdateOfficeid']);
    Route::put('/updatePassword', ['as' => 'admin.user.updatePassword', 'uses' => 'UserController@UpdatePassword']);
    Route::post('/user/changeStatus', ['as' => 'admin.user.changeActiveStatus', 'uses' => 'UserController@changeActiveStatus']);
    Route::post('/user/changeAbleStatus', ['as' => 'admin.user.changeAbleStatus', 'uses' => 'UserController@changeAbleStatus']);
    Route::get('/user/download', ['as' => 'admin.user.download', 'uses' => 'UserController@userDownload']);

    Route::get('/retailer', ['as' => 'admin.retailer', 'uses' => 'UserController@RetailerView']);
    Route::get('/retailer/create', ['as' => 'admin.retailer.create', 'uses' => 'UserController@RetailerCreate']);
    Route::get('/retailer/download', ['as' => 'admin.retailer.download', 'uses' => 'UserController@retailerDownload']);
    Route::post('/retailer', ['as' => 'admin.retailer.store', 'uses' => 'UserController@RetailerViewStore']);

    Route::post('/user/enable', ['as' => 'admin.user.enable', 'uses' => 'AdminController@UserEnable']);

    Route::post('/user/disable', ['as' => 'admin.user.disable', 'uses' => 'AdminController@UserDisable']);


    Route::post('/user/AddRetailer', ['as' => 'admin.user.addRetailer', 'uses' => 'AdminController@AddRetailer']);
    Route::get('/user/deleteRetailer/{id}', ['as' => 'admin.user.deleteRetailer', 'uses' => 'AdminController@DeleteRetailer'])->where(['id' => '[0-9]+']);

    Route::post('/user/AddSr', ['as' => 'admin.user.addSr', 'uses' => 'AdminController@AddSr']);
    Route::get('/user/deleteSr/{id}', ['as' => 'admin.user.deleteSr', 'uses' => 'AdminController@DeleteSr'])->where(['id' => '[0-9]+']);

    Route::post('/user/AddDistrict', ['as' => 'admin.user.addDistrict', 'uses' => 'AdminController@AddDistrict']);
    Route::get('/user/deleteDistrict/{id}', ['as' => 'admin.user.deleteDistrict', 'uses' => 'AdminController@DeleteDistrict'])->where(['id' => '[0-9]+']);

    Route::post('/user/AddUpazila', ['as' => 'admin.user.addUpazila', 'uses' => 'AdminController@AddUpazila']);
    Route::get('/user/deleteUpazila/{id}', ['as' => 'admin.user.deleteUpazila', 'uses' => 'AdminController@DeleteUpazila'])->where(['id' => '[0-9]+']);


    Route::get('/user/CheckRetailer', ['as' => 'admin.user.CheckRetailer', 'uses' => 'AdminController@CheckRetailerView']);
    Route::post('/user/CheckRetailer', ['as' => 'admin.user.CheckRetailer.store', 'uses' => 'AdminController@CheckRetailerViewStore']);




    Route::get('/singleuser/{id}', ['as' => 'admin.singleuser', 'uses' => 'AdminController@SingleUser'])->where(['id' => '[0-9]+']);


    Route::put('/updateAlternativeEmail', ['as' => 'admin.distributor.updateAlternativeEmail', 'uses' => 'AdminController@UpdateAlternativeEmail']);

    Route::put('/updateEmail', ['as' => 'admin.distributor.updateEmail', 'uses' => 'AdminController@UpdateEmail']);

    // Route for user=========






    // wcheck area===============

    Route::group(['prefix' => 'wcheck'], function () {

        Route::get('/wcheckProduct', ['as' => 'admin.wcheckProduct', 'uses' => 'AdminController@WcheckProductView']);
        Route::post('/wcheckProduct', ['as' => 'admin.wcheckProduct.store', 'uses' => 'AdminController@WcheckProductViewStore']);


        Route::put('/wcheckProduct/repalce', ['as' => 'admin.wcheckProduct.repalce', 'uses' => 'AdminController@WcheckProductReplace']);

        Route::put('/wcheckProduct/service', ['as' => 'admin.wcheckProduct.service', 'uses' => 'AdminController@WcheckProductService']);


        Route::put('/wcheckProduct/repalce/update', ['as' => 'admin.wcheckProduct.repalce.update', 'uses' => 'AdminController@WcheckProductServiceUpdate']);

        Route::put('/wcheckProduct/service/update', ['as' => 'admin.wcheckProduct.service.update', 'uses' => 'AdminController@WcheckProductReplaceUpdate']);

        Route::delete('/wcheckProduct/repalce/delete/{id}', ['as' => 'admin.wcheckProduct.repalce.delete', 'uses' => 'AdminController@WcheckProductReplaceDelete'])->where(['id' => '[0-9]+']);

        Route::delete('/wcheckProduct/service/delete/{id}', ['as' => 'admin.wcheckProduct.service.delete', 'uses' => 'AdminController@WcheckProductServiceDelete'])->where(['id' => '[0-9]+']);


    });

    // wcheck area===============


    // return area===============

    Route::group(['prefix' => 'return'], function () {

        Route::get('/returnProductAll', ['as' => 'admin.returnProductAll', 'uses' => 'AdminController@ReturnProductViewAll']);

        Route::get('/returnProduct', ['as' => 'admin.returnProduct', 'uses' => 'AdminController@ReturnProductView']);

        Route::post('/returnProduct', ['as' => 'admin.returnProduct.store', 'uses' => 'AdminController@ReturnProductViewStore']);
        Route::put('/returnProduct', ['as' => 'admin.returnProduct.update', 'uses' => 'AdminController@ReturnProductUpdate']);
        Route::delete('/returnProduct/{id}', ['as' => 'admin.returnProduct.delete', 'uses' => 'AdminController@ReturnProductDelete'])->where(['id' => '[0-9]+']);

    });

    // return area===============

    // dontwarry area===============

    Route::group(['prefix' => 'dontwarry'], function () {

        Route::get('/dontWorry', ['as' => 'admin.dontWorry', 'uses' => 'AdminController@DontWorryView']);
        //Route::post('/dontWorry', ['as'=>'admin.dontWorry.store','uses'=>'AdminController@DontWorryViewStore']);
        //Route::put('/dontWorry', ['as'=>'admin.dontWorry.update','uses'=>'AdminController@DontWorryUpdate']);
        Route::delete('/dontWorry/{id}', ['as' => 'admin.dontWorry.delete', 'uses' => 'AdminController@DontWorryDelete'])->where(['id' => '[0-9]+']);

        Route::post('/dontWorry/active', ['as' => 'admin.dontWorry.active', 'uses' => 'AdminController@DontWorryActive']);
        Route::post('/dontWorry/inactive', ['as' => 'admin.dontWorry.inactive', 'uses' => 'AdminController@DontWorryInactive']);
    });

    // dontwarry area===============



    // reports area===============

    Route::group(['prefix' => 'reports'], function () {
        Route::get('/dailyAdminSalesReport', ['as' => 'admin.dailyDistributorSalesReport', 'uses' => 'AdminController@DailyDistributorSalesReportView']);
        Route::post('/dailyAdminSalesReport', ['as' => 'admin.dailyDistributorSalesReport.store', 'uses' => 'AdminController@DailyDistributorSalesReportViewStore']);
        Route::get('/dailyDistributorSalesReport/print/{user_id?}/{fdstart?}/{fdend?}', ['as' => 'admin.dailyDistributorSalesReport.print', 'uses' => 'AdminController@DailyDistributorSalesReportViewPrint']);

        //Route::put('/salereturn', ['as'=>'admin.salereturn.return.update','uses'=>'AdminController@SalereturnReturnUpdate']);

    });


    Route::group(['prefix' => 'reports'], function () {
        Route::get('/incompleteReport', ['as' => 'admin.incompleteReport', 'uses' => 'ReportController@incompleteReport']);
        Route::get('/incompleteIMEIView/{id}/{productId}', ['as' => 'incompleteIMEIView', 'uses' => 'ReportController@incompleteIMEIView']);

        Route::get('/pendingOrder', ['as' => 'admin.pendingOrder', 'uses' => 'OrderController@pendingOrder']);


    });


    Route::group(['prefix' => 'reports'], function () {

        Route::get('/dailyCampaignReport', ['as' => 'admin.dailyCampaignReport', 'uses' => 'AdminController@DailyCampaignReportView']);
        Route::post('/dailyCampaignReport', ['as' => 'admin.dailyCampaignReport.store', 'uses' => 'AdminController@DailyCampaignReportViewStore']);
        Route::get('/dailyCampaignReport/print/{user_id?}/{fdstart?}/{fdend?}', ['as' => 'admin.dailyCampaignReport.print', 'uses' => 'AdminController@DailyCampaignReportViewPrint']);

    });



    Route::group(['prefix' => 'reports'], function () {
        Route::get('/dailyRetailerStockReport', ['as' => 'admin.dailyRetailerStockReport', 'uses' => 'AdminController@DailyRetailerStockReportView']);

        Route::post('/dailyRetailerStockReport', ['as' => 'admin.dailyRetailerStockReport.store', 'uses' => 'AdminController@DailyRetailerStockReportViewStore']);
    });


    Route::group(['prefix' => 'reports'], function () {


        Route::get('/dailyReturnReport', ['as' => 'admin.dailyReturnReport', 'uses' => 'AdminController@DailyReturnReportView']);
        Route::post('/dailyReturnReport', ['as' => 'admin.dailyReturnReport.store', 'uses' => 'AdminController@DailyReturnReportViewStore']);


        Route::get('/dailyRTSMSReport', ['as' => 'admin.dailyRTSMSReport', 'uses' => 'AdminController@DailyRTSMSReportView']);
        Route::post('/dailyRTSMSReport', ['as' => 'admin.dailyRTSMSReport.store', 'uses' => 'AdminController@DailyRTSMSReportViewStore']);

        //================VAT Report======================
        Route::get('/vatReport', ['as' => 'admin.vatReport', 'uses' => 'ReportController@vatReport']);
        Route::post('/vatReportStore', ['as' => 'admin.vatReportStore', 'uses' => 'ReportController@vatReportStore']);
        Route::get('/vatReportDownload', ['as' => 'admin.vatReportDownload', 'uses' => 'ReportController@fullVatReportDownload']);
        Route::get('/currentMonthVatReportDownload', ['as' => 'admin.currentMonthVatReportDownload', 'uses' => 'ReportController@currentMonthVatReportDownload']);
        Route::get('/memoFunction', ['as' => 'memoFunction', 'uses' => 'AdminController@memoFunction']);
        Route::post('/memoUpload', ['as' => 'memoUpload', 'uses' => 'AdminController@memoUpload']);

        Route::get('/retailerCheckReport', ['as' => 'admin.retailerCheckReport', 'uses' => 'AdminController@RetailerCheckReportView']);
        Route::post('/retailerCheckReport', ['as' => 'admin.retailerCheckReport.store', 'uses' => 'AdminController@RetailerCheckReportViewStore']);


        Route::get('/tsoCheckReport', ['as' => 'admin.tsoCheckReport', 'uses' => 'AdminController@tsoCheckReportView']);
        Route::post('/tsoCheckReport', ['as' => 'admin.tsoCheckReport.store', 'uses' => 'AdminController@tsoCheckReportViewStore']);

        Route::get('/srCheckReport', ['as' => 'admin.srCheckReport', 'uses' => 'AdminController@srCheckReportView']);
        Route::post('/srCheckReport', ['as' => 'admin.srCheckReport.store', 'uses' => 'AdminController@srCheckReportViewStore']);

        Route::get('/imei-life-cycle-report', ['as' => 'admin.dailyimeivReport', 'uses' => 'ReportController@ImeiLifeCycleReport']);
        Route::post('/imei-life-cycle-report', ['as' => 'admin.dailyimeivReport.download', 'uses' => 'ReportController@ImeiLifeCycleReportDownload']);
        Route::post('/imei-life-cycle-report-single', ['as' => 'singleIMEI', 'uses' => 'ReportController@ImeiLifeCycleReportDownloadSingle']);

        Route::get('/dailyPurchaseSaleReport', ['as' => 'admin.dailyPurchaseSaleReport', 'uses' => 'AdminController@DailyPurchaseSaleReportView']);
        Route::post('/dailyPurchaseSaleReport', ['as' => 'admin.dailyPurchaseSaleReport.store', 'uses' => 'AdminController@DailyPurchaseSaleReportViewStore']);

        Route::get('/imeiall', ['as' => 'imeiall', 'uses' => 'AdminController@imeiall']);


        Route::put('/purchase', ['as' => 'admin.purchase.update', 'uses' => 'AdminController@PurchaseUpdate']);
        Route::delete('/purchase/{id}', ['as' => 'admin.purchase.delete', 'uses' => 'AdminController@PurchaseDestroy'])->where(['id' => '[0-9]+']);

        Route::post('/purchase/active', ['as' => 'admin.purchase.active', 'uses' => 'AdminController@PurchaseActive']);

        Route::post('/purchase/inactive', ['as' => 'admin.purchase.inactive', 'uses' => 'AdminController@PurchaseInactive']);

        Route::delete('/saleout/{id}', ['as' => 'admin.saleout.delete', 'uses' => 'AdminController@SaleoutDestroy'])->where(['id' => '[0-9]+']);

        Route::put('/sale', ['as' => 'admin.sale.update', 'uses' => 'AdminController@SaleUpdate']);

        Route::delete('/sale/{id}', ['as' => 'admin.sale.delete', 'uses' => 'AdminController@SaleDestroy'])->where(['id' => '[0-9]+']);

        Route::post('/dos_report', ['as' => 'admin.dosReport.store', 'uses' => 'AdminController@dosReportViewStore']);

        Route::get('/dos', ['as' => 'admin.dosReport', 'uses' => 'AdminController@dosView']);
        Route::post('/dosRetailerReport', ['as' => 'admin.dosRetailerReport.store', 'uses' => 'AdminController@dosRetailerStore']);
        Route::get('/dos_retailer', ['as' => 'admin.retailerDosReport', 'uses' => 'AdminController@dosRetailer']);

    });






    // reports area===============









});


//Sales Route

Route::group(['prefix' => 'sales-panel', 'middleware' => 'web'], function () {
    Route::get('/test', ['as' => 'sales.test', 'uses' => 'SalesController@Test']);
    Route::get('/dashboard', ['as' => 'sales.dashboard', 'uses' => 'SalesController@DashboardView']);

});


//Service Center Route

Route::group(['prefix' => 'service-panel', 'middleware' => 'web'], function () {
    Route::get('/test', ['as' => 'service.test', 'uses' => 'ServiceController@Test']);
    Route::get('/dashboard', ['as' => 'service.dashboard', 'uses' => 'ServiceController@DashboardView']);

    // wcheck area===============

    Route::group(['prefix' => 'wcheck'], function () {

        Route::get('/wcheckProduct', ['as' => 'service.wcheckProduct', 'uses' => 'ServiceController@WcheckProductView']);
        Route::post('/wcheckProduct', ['as' => 'service.wcheckProduct.store', 'uses' => 'ServiceController@WcheckProductViewStore']);

        Route::put('/wcheckProduct/repalce', ['as' => 'service.wcheckProduct.repalce', 'uses' => 'ServiceController@WcheckProductReplace']);


    });

    // wcheck area===============

});
//Service Management Panel Start
Route::group(['prefix' => 'service_management-panel', 'middleware' => ['web']], function () {

    Route::get('/dashboard', ['as' => 'serviceManagement.dashboard', 'uses' => 'ServiceManagementController@DashboardView']);
    Route::get('/download', ['as' => 'serviceManagement.download', 'uses' => 'ServiceManagementController@download']);

    Route::get('/receive_product', ['as' => 'serviceManagement.receiveProduct', 'uses' => 'ServiceManagementController@receive_product']);
    Route::get('/receive_product_excel', ['as' => 'serviceManagement.receiveProductExcel', 'uses' => 'ServiceManagementController@receive_product_download']);
    Route::get('/check_product', ['as' => 'serviceManagement.checkProduct', 'uses' => 'ServiceManagementController@checkProduct']);
    Route::get('/check_product_excel', ['as' => 'serviceManagement.checkProductExcel', 'uses' => 'ServiceManagementController@checkProductExcel']);
    Route::get('/deliver_product', ['as' => 'serviceManagement.deliverProduct', 'uses' => 'ServiceManagementController@deliverProduct']);
    Route::get('/deliver_product_excel', ['as' => 'serviceManagement.deliverProductExcel', 'uses' => 'ServiceManagementController@deliverProductExcel']);

    Route::get('/approve_deliver_product', ['as' => 'serviceManagement.approveDeliverProduct', 'uses' => 'ServiceManagementController@approveDeliverProduct']);
    Route::get('/approve_deliver_product_excel', ['as' => 'serviceManagement.approveDeliverProductExcel', 'uses' => 'ServiceManagementController@approveDeliverProductExcel']);

    Route::get('/canceled_product', ['as' => 'serviceManagement.canceledProduct', 'uses' => 'ServiceManagementController@canceledProduct']);
    Route::get('/canceled_product_excel', ['as' => 'serviceManagement.canceledProductExcel', 'uses' => 'ServiceManagementController@canceledProductExcel']);

    Route::get('/canceled_delivered_product', ['as' => 'serviceManagement.canceledDeliveredProduct', 'uses' => 'ServiceManagementController@canceledDeliveredProduct']);
    Route::post('/receive_confirm/{id?}', ['as' => 'serviceManagement.receiveConfirm', 'uses' => 'ServiceManagementController@receive_confirm']);
    Route::post('/approve-receive_product/{id?}', ['as' => 'serviceManagement.approveReceiveProduct', 'uses' => 'ServiceManagementController@approveReceiveProduct']);
    Route::post('/cancel_product/{id?}', ['as' => 'serviceManagement.cancelProduct', 'uses' => 'ServiceManagementController@cancelProduct']);
    Route::post('/cancel_delivery_product/{id?}', ['as' => 'serviceManagement.cancelDeliveryProduct', 'uses' => 'ServiceManagementController@cancelDeliveryProduct']);
    Route::post('/approve_delivery/{id?}', ['as' => 'serviceManagement.approveDelivery', 'uses' => 'ServiceManagementController@approveDelivery']);
    Route::post('/send_to_receive/{id?}', ['as' => 'serviceManagement.sendToReceive', 'uses' => 'ServiceManagementController@sendToReceive']);
    Route::post('/cancel_delivery_confirm/{id?}', ['as' => 'serviceManagement.cancelDeliveryConfirm', 'uses' => 'ServiceManagementController@cancelDeliveryConfirm']);

    Route::post('/receive-report-store', ['as' => 'serviceManagement.receiveReportStore', 'uses' => 'ServiceManagementController@receiveReportStore']);
    Route::post('/check-report-store', ['as' => 'serviceManagement.checkReportStore', 'uses' => 'ServiceManagementController@checkReportStore']);
    Route::post('/deliver-report-store', ['as' => 'serviceManagement.deliverReportStore', 'uses' => 'ServiceManagementController@deliverReportStore']);
    Route::post('/approve-deliver-report-store', ['as' => 'serviceManagement.approveDeliverReportStore', 'uses' => 'ServiceManagementController@approveDeliverReportStore']);
    Route::post('/cancel-report-store', ['as' => 'serviceManagement.cancelReportStore', 'uses' => 'ServiceManagementController@cancelReportStore']);
    Route::post('/cancel-delivered-report-store', ['as' => 'serviceManagement.cancelDeliverdReportStore', 'uses' => 'ServiceManagementController@cancelDeliverdReportStore']);
    Route::post('/bulk-upload', ['as' => 'serviceManagement.bulkUpload', 'uses' => 'ServiceManagementController@bulkUpload']);
    Route::get('/bulk-upload', ['as' => 'serviceManagement.bulkUploadView', 'uses' => 'ServiceManagementController@bulkUploadView']);


});


//warehouse  Route

Route::group(['prefix' => 'warehouse-panel', 'middleware' => 'web'], function () {
    Route::get('/test', ['as' => 'warehouse.test', 'uses' => 'WarehouseController@Test']);
    Route::get('/dashboard', ['as' => 'warehouse.dashboard', 'uses' => 'WarehouseController@DashboardView']);
    Route::get('/orderReport', ['as' => 'warehouse.orderReport', 'uses' => 'WarehouseController@orderReport']);
    Route::post('/orderReportStore', ['as' => 'warehouse.orderReport.store', 'uses' => 'WarehouseController@orderReportStore']);

    // Bulk Download

    Route::get('/distributorDeliveryDownload', ['as' => 'distributorDeliveryDownload', 'uses' => 'WarehouseController@distributorDeliveryDownload']);
    Route::get('/deliveryDownload', ['as' => 'deliveryDownload', 'uses' => 'WarehouseController@deliveryDownload']);
    Route::get('/formatDownload/{id}', ['as' => 'formatDownload', 'uses' => 'WarehouseController@formatDownload']);
    Route::post('/formatUpload', ['as' => 'formatUpload', 'uses' => 'WarehouseController@formatUpload']);



    //Bulk Download

    Route::get('/dataSync', ['as' => 'warehouse.dataSink', 'uses' => 'WarehouseController@dataSink']);
    Route::get('/refreshStock', ['as' => 'warehouse.refreshStock', 'uses' => 'WarehouseController@refreshStock']);

    //Distributor Wise Report Starts Here

    Route::get('/distributorDeliveryReport', ['as' => 'distributorDeliveryReport', 'uses' => 'WarehouseController@distributorDeliveryReport']);
    Route::post('/distributorDeliveryReportStore', ['as' => 'distributorDeliveryReportStore', 'uses' => 'WarehouseController@distributorDeliveryReportStore']);
    Route::get('/deliveryReport', ['as' => 'deliveryReport', 'uses' => 'WarehouseController@deliveryReport']);
    Route::post('/deliveryReportStore', ['as' => 'deliveryReportStore', 'uses' => 'WarehouseController@deliveryReportStore']);

    //Distributor Wise Report Ends Here

    //Product Wise Report Starts Here

    Route::get('/stockReceiveReport', ['as' => 'stockReceiveReport', 'uses' => 'WarehouseController@stockReceiveReport']);
    Route::post('/stockReceiveReport', ['as' => 'stockReceiveReportStore', 'uses' => 'WarehouseController@stockReceiveReportStore']);
    Route::get('/stockDeliveryReport', ['as' => 'stockDeliveryReport', 'uses' => 'WarehouseController@stockDeliveryReport']);
    Route::post('/stockDeliveryReport', ['as' => 'stockDeliveryReportStore', 'uses' => 'WarehouseController@stockDeliveryReportStore']);
    Route::get('/currentStockReport', ['as' => 'currentStockReport', 'uses' => 'WarehouseController@currentStockReport']);
    Route::post('/currentStockReport', ['as' => 'currentStockReportStore', 'uses' => 'WarehouseController@currentStockReportStore']);

    //Product Wise Report Ends Here

    //Receive and Delivery Report STarts Here

    Route::get('/receiveAndDeliveryReport', ['as' => 'receiveAndDeliveryReport', 'uses' => 'WarehouseController@receiveAndDeliveryReport']);
    Route::get('/currentMonthReceiveReport', ['as' => 'currentMonthReceiveReport', 'uses' => 'WarehouseController@currentMonthReceiveReport']);
    Route::get('/currentMonthDeliveryReport', ['as' => 'currentMonthDeliveryReport', 'uses' => 'WarehouseController@currentMonthDeliveryReport']);
    Route::post('/allReceiveReport', ['as' => 'allReceiveReport', 'uses' => 'WarehouseController@allReceiveReport']);
    Route::post('/allDeliveryReport', ['as' => 'allDeliveryReport', 'uses' => 'WarehouseController@allDeliveryReport']);

    //Receive and Delivery Report Ends Here

    Route::get('printinvoice/{id}', ['as' => 'printinvoice', 'uses' => 'WarehouseController@printinvoice']);
    Route::get('/varifyserialno/{no?}', ['as' => 'ajax.varifyimeino', 'uses' => 'WarehouseController@varifyimeino']);

    Route::post('/updateimei', ['as' => 'update.imei', 'uses' => 'OrderimieditController@update']);
    Route::get('/ordersimei/{id}', ['as' => 'ordersimei.edit', 'uses' => 'OrderspostingdetailsimiController@editexistingimi']);

    Route::resource('orderspostingdetailsimis', 'OrderspostingdetailsimiController');

    Route::get('add_pending_imei/{id}', ['as' => 'add_pending_imei', 'uses' => 'OrderspostingdetailsimiController@add_pending_imei']);


    Route::post('/orderSearch', ['as' => 'orderSearch', 'uses' => 'OrderspostingdetailsimiController@orderSearch']);


    Route::get('pree-sell/{id}', ['as' => 'preesell.add', 'uses' => 'OrderspostingdetailsimiController@PreeSellconfirmeation']);
    Route::get('delivery_info/{id}', ['as' => 'deliveryInfo_edit', 'uses' => 'OrderspostingdetailsimiController@deliveryInfo_edit']);

    Route::get('chalan/{id}', ['as' => 'chalan.get', 'uses' => 'OrderspostingdetailsimiController@deliverychalan']);

    // wcheck area===============

    Route::group(['prefix' => 'wcheck'], function () {

        Route::get('/wcheckProduct', ['as' => 'warehouse.wcheckProduct', 'uses' => 'WarehouseController@WcheckProductView']);
        Route::post('/wcheckProduct', ['as' => 'warehouse.wcheckProduct.store', 'uses' => 'WarehouseController@WcheckProductViewStore']);

        Route::put('/wcheckProduct/repalce', ['as' => 'warehouse.wcheckProduct.repalce', 'uses' => 'WarehouseController@WcheckProductReplace']);


    });

    // wcheck area===============


    // Route for upload1=========

    Route::get('/upload1', ['as' => 'warehouse.upload1', 'uses' => 'WarehouseController@Upload1View']);
    Route::post('/upload1', ['as' => 'warehouse.upload1.store', 'uses' => 'WarehouseController@Upload1ViewStore']);
    //Route::put('/upload1', ['as'=>'admin.upload1.update','uses'=>'AdminController@Upload1Update']);
    //Route::delete('/upload1/{id}', ['as'=>'admin.upload1.delete','uses'=>'AdminController@Upload1Destroy'])->where(['id' => '[0-9]+']);

    // Route for upload1=========

    // Route for Product Stock =========
    Route::get('/stockExcel', ['as' => 'warehouse.stock.excel', 'uses' => 'WarehouseController@StockViewExcel']);
    Route::get('/stock', ['as' => 'warehouse.stock', 'uses' => 'WarehouseController@StockView']);
    Route::post('/stock', ['as' => 'warehouse.stock.store', 'uses' => 'WarehouseController@StockViewStore']);

    Route::put('/stock', ['as' => 'warehouse.stock.update', 'uses' => 'WarehouseController@StockUpdate']);
    Route::delete('/stock/{id}', ['as' => 'warehouse.stock.delete', 'uses' => 'WarehouseController@StockDestroy'])->where(['id' => '[0-9]+']);


    Route::get('/stocktable', ['as' => 'warehouse.stocktable', 'uses' => 'WarehouseController@StockViews']);

});

//accounts  Route

Route::group(['prefix' => 'accounts-panel', 'middleware' => 'web'], function () {

    Route::resource('orderspostings', 'OrderspostingController');

    Route::get('/order/comparison', ['as' => 'account.comparison', 'uses' => 'OrderspostingController@orderComparison']);

    Route::get('postinginvoice/{id}', ['as' => 'postinginvoice.print', 'uses' => 'OrderspostingController@postinginvoice']);

    Route::get('/orader/{orader_no}', ['as' => 'account.details', 'uses' => 'OrderspostingController@ShowOrader']);

    Route::get('/ordersposting_delete/{id}', ['as' => 'orderposting_delete', 'uses' => 'OrderspostingController@delete']);
    Route::get('/ordersposting_reverse/{id}', ['as' => 'orderposting_reverse', 'uses' => 'OrderspostingController@reverse']);

    Route::get('/test', ['as' => 'accounts.test', 'uses' => 'AccountsController@Test']);
    Route::get('/dashboard', ['as' => 'accounts.dashboard', 'uses' => 'AccountsController@DashboardView']);

    //================VAT Report======================

    Route::get('/vatReport', ['as' => 'accounts.vatReport', 'uses' => 'AccountsController@vatReport']);
    Route::post('/vatReportStore', ['as' => 'accounts.vatReportStore', 'uses' => 'AccountsController@vatReportStore']);
    Route::get('/vatDownload', ['as' => 'accounts.vatDownload', 'uses' => 'AccountsController@vatDownload']);
    Route::get('/currentMonthVatDownload', ['as' => 'accounts.currentMonthVatDownload', 'uses' => 'AccountsController@currentMonthVatDownload']);

    Route::get('/searchOrder/{id}', ['as' => 'accounts.searchOrder', 'uses' => 'AccountsController@searchOrder']);



    //================VAT Report======================

    //================CLOSE Report======================

    Route::get('/closeReport', ['as' => 'accounts.closeReport', 'uses' => 'AccountsController@closeReport']);
    Route::post('closeReportStore', ['as' => 'accounts.closeReportStore', 'uses' => 'AccountsController@closeReportStore']);
    Route::get('/pendingReport', ['as' => 'accounts.pendingReport', 'uses' => 'AccountsController@pendingReport']);


    //================CLOSE Report======================

    // Bulk Download

    Route::get('/distributorDeliveryExcel', ['as' => 'distributorDeliveryExcel', 'uses' => 'AccountsController@distributorDeliveryExcel']);
    Route::get('/deliveryExcel', ['as' => 'deliveryExcel', 'uses' => 'AccountsController@deliveryExcel']);


    //Bulk Download



    Route::group(['prefix' => 'reports'], function () {

        Route::get('/todaysProductWiseReport', ['as' => 'accounts.todaysProductWiseReport', 'uses' => 'ReportController@todaysProductWiseReport']);

        //Distributor Wise Report Starts Here

        Route::get('/distributorDeliveryReport', ['as' => 'accounts.distributorDeliveryReport', 'uses' => 'AccountsController@distributorDeliveryReport']);
        Route::post('/distributorDeliveryReportStore', ['as' => 'accounts.distributorDeliveryReportStore', 'uses' => 'AccountsController@distributorDeliveryReportStore']);
        Route::get('/deliveryReport', ['as' => 'accounts.deliveryReport', 'uses' => 'AccountsController@deliveryReport']);
        Route::post('/deliveryReportStore', ['as' => 'accounts.deliveryReportStore', 'uses' => 'AccountsController@deliveryReportStore']);

        //Distributor Wise Report Ends Here

        //Product Wise Report Starts Here

        Route::get('/stockReceiveReport', ['as' => 'accounts.stockReceiveReport', 'uses' => 'AccountsController@stockReceiveReport']);
        Route::post('/stockReceiveReport', ['as' => 'accounts.stockReceiveReportStore', 'uses' => 'AccountsController@stockReceiveReportStore']);
        Route::get('/stockDeliveryReport', ['as' => 'accounts.stockDeliveryReport', 'uses' => 'AccountsController@stockDeliveryReport']);
        Route::post('/stockDeliveryReport', ['as' => 'accounts.stockDeliveryReportStore', 'uses' => 'AccountsController@stockDeliveryReportStore']);
        Route::get('/currentStockReport', ['as' => 'accounts.currentStockReport', 'uses' => 'AccountsController@currentStockReport']);
        Route::post('/currentStockReport', ['as' => 'accounts.currentStockReportStore', 'uses' => 'AccountsController@currentStockReportStore']);

        //Product Wise Report Ends Here


    });
    // wcheck area===============

    Route::group(['prefix' => 'wcheck'], function () {

        Route::get('/wcheckProduct', ['as' => 'accounts.wcheckProduct', 'uses' => 'AccountsController@WcheckProductView']);
        Route::post('/wcheckProduct', ['as' => 'accounts.wcheckProduct.store', 'uses' => 'AccountsController@WcheckProductViewStore']);

        Route::put('/wcheckProduct/repalce', ['as' => 'accounts.wcheckProduct.repalce', 'uses' => 'AccountsController@WcheckProductReplace']);

        Route::get('/dailyStockReport', ['as' => 'accounts.dailyStockReport', 'uses' => 'AccountsController@DailyStockReportView']);
        Route::post('/dailyStockReport', ['as' => 'accounts.dailyStockReport.store', 'uses' => 'AccountsController@DailyStockReportViewStore']);


    });

    // wcheck area===============

});




//Topmanagement Route

Route::group(['prefix' => 'topmanagement-panel', 'middleware' => 'web'], function () {


    Route::get('/test', ['as' => 'topmanagement.test', 'uses' => 'TopmanagementController@Test']);
    Route::get('/dashboard', ['as' => 'topmanagement.dashboard', 'uses' => 'TopmanagementController@DashboardView']);




    // Route for topmanagement=========
    Route::get('/topmanagement', ['as' => 'topmanagement.topmanagement', 'uses' => 'TopmanagementController@TopmanagementView']);
    //Route::post('/topmanagement', ['as'=>'topmanagement.topmanagement.store','uses'=>'TopmanagementController@TopmanagementViewStore']);
    Route::put('/topmanagement', ['as' => 'topmanagement.topmanagement.update', 'uses' => 'TopmanagementController@TopmanagementUpdate']);
    Route::put('/updatePassword', ['as' => 'topmanagement.topmanagement.updatePassword', 'uses' => 'TopmanagementController@UpdatePassword']);
    // Route for topmanagement=========



    // wcheck area===============

    Route::group(['prefix' => 'wcheck'], function () {

        Route::get('/wcheckProduct', ['as' => 'topmanagement.wcheckProduct', 'uses' => 'TopmanagementController@WcheckProductView']);
        Route::post('/wcheckProduct', ['as' => 'topmanagement.wcheckProduct.store', 'uses' => 'TopmanagementController@WcheckProductViewStore']);

        Route::put('/wcheckProduct/repalce', ['as' => 'topmanagement.wcheckProduct.repalce', 'uses' => 'TopmanagementController@WcheckProductReplace']);


    });

    // wcheck area===============

    Route::group(['prefix' => 'reports'], function () {

        Route::get('/wodReport', ['as' => 'topmanagement.wodReport', 'uses' => 'TopmanagementController@wodReport']);
        Route::post('/wodReportStore', ['as' => 'topmanagement.wodReportStore', 'uses' => 'TopmanagementController@wodReportStore']);

        Route::get('/primarySecondaryDLReport', ['as' => 'topmanagement.primarySecondaryDLReport', 'uses' => 'TopmanagementController@primarySecondaryDLReport']);
        Route::post('/primarySecondaryDLReport', ['as' => 'topmanagement.primarySecondaryDLReportStore', 'uses' => 'TopmanagementController@primarySecondaryDLReportStore']);

        Route::get('/priExcel', ['as' => 'topmanagement.stock.pexcel', 'uses' => 'TopmanagementController@PrimaryViewExcel']);
        Route::get('/secExcel', ['as' => 'topmanagement.stock.sexcel', 'uses' => 'TopmanagementController@SeconderyViewExcel']);

        Route::get('/dosReport', ['as' => 'topmanagement.dosReport', 'uses' => 'TopmanagementController@dosReport']);
        Route::post('/dosReportStore', ['as' => 'topmanagement.dosReportStore', 'uses' => 'TopmanagementController@dosReportStore']);

        Route::get('/distributorImeiStockReport', ['as' => 'topmanagement.distributorImeiStockReport', 'uses' => 'TopmanagementController@distributorImeiStockReport']);
        Route::get('/retailerImeiStockReport', ['as' => 'topmanagement.retailerImeiStockReport', 'uses' => 'TopmanagementController@retailerImeiStockReport']);

    });


    // reports area===============

    Route::group(['prefix' => 'reports'], function () {

        Route::get('/dailyTopmanagementSalesReport', ['as' => 'topmanagement.dailyDistributorSalesReport', 'uses' => 'TopmanagementController@DailyDistributorSalesReportView']);
        Route::post('/dailyTopmanagementSalesReport', ['as' => 'topmanagement.dailyDistributorSalesReport.store', 'uses' => 'TopmanagementController@DailyDistributorSalesReportViewStore']);
        Route::get('/dailyDistributorSalesReport/print/{user_id?}/{fdstart?}/{fdend?}', ['as' => 'topmanagement.dailyDistributorSalesReport.print', 'uses' => 'TopmanagementController@DailyDistributorSalesReportViewPrint']);

        //Route::put('/salereturn', ['as'=>'topmanagement.salereturn.return.update','uses'=>'TopmanagementController@SalereturnReturnUpdate']);

    });



    Route::group(['prefix' => 'reports'], function () {
        Route::get('/dailyRetailerStockReport', ['as' => 'topmanagement.dailyRetailerStockReport', 'uses' => 'TopmanagementController@DailyRetailerStockReportView']);
        Route::post('/dailyRetailerStockReport', ['as' => 'topmanagement.dailyRetailerStockReport.store', 'uses' => 'TopmanagementController@DailyRetailerStockReportViewStore']);
    });

    Route::group(['prefix' => 'reports'], function () {

        Route::get('/dailySalesReport', ['as' => 'topmanagement.dailySalesReport', 'uses' => 'TopmanagementController@DailySalesReportView']);
        Route::post('/dailySalesReport', ['as' => 'topmanagement.dailySalesReport.store', 'uses' => 'TopmanagementController@DailySalesReportViewStore']);
        Route::get('/dailySalesReport/print/{user_id?}/{fdstart?}/{fdend?}', ['as' => 'topmanagement.dailySalesReport.print', 'uses' => 'TopmanagementController@DailySalesReportViewPrint']);
    });


    Route::group(['prefix' => 'reports'], function () {

        Route::get('/dailyCampaignReport', ['as' => 'topmanagement.dailyCampaignReport', 'uses' => 'TopmanagementController@DailyCampaignReportView']);
        Route::post('/dailyCampaignReport', ['as' => 'topmanagement.dailyCampaignReport.store', 'uses' => 'TopmanagementController@DailyCampaignReportViewStore']);
        Route::get('/dailyCampaignReport/print/{user_id?}/{fdstart?}/{fdend?}', ['as' => 'topmanagement.dailyCampaignReport.print', 'uses' => 'TopmanagementController@DailyCampaignReportViewPrint']);

    });



    Route::group(['prefix' => 'reports'], function () {

        Route::get('/dailyReplaceReport', ['as' => 'topmanagement.dailyReplaceReport', 'uses' => 'TopmanagementController@DailyReplaceReportView']);
        Route::post('/dailyReplaceReport', ['as' => 'topmanagement.dailyReplaceReport.store', 'uses' => 'TopmanagementController@DailyReplaceReportViewStore']);
        Route::get('/dailyReplaceReport/print/{user_id?}/{fdstart?}/{fdend?}', ['as' => 'topmanagement.dailyReplaceReport.print', 'uses' => 'TopmanagementController@DailyReplaceReportViewPrint']);


        Route::get('/dailyStockReport', ['as' => 'topmanagement.dailyStockReport', 'uses' => 'TopmanagementController@DailyStockReportView']);
        Route::post('/dailyStockReport', ['as' => 'topmanagement.dailyStockReport.store', 'uses' => 'TopmanagementController@DailyStockReportViewStore']);

        Route::get('/dailyPurchaseSaleReport', ['as' => 'topmanagement.dailyPurchaseSaleReport', 'uses' => 'TopmanagementController@DailyPurchaseSaleReportView']);
        Route::post('/dailyPurchaseSaleReport', ['as' => 'topmanagement.dailyPurchaseSaleReport.store', 'uses' => 'TopmanagementController@DailyPurchaseSaleReportViewStore']);

        Route::put('/purchase', ['as' => 'topmanagement.purchase.update', 'uses' => 'TopmanagementController@PurchaseUpdate']);


        Route::delete('/purchase/{id}', ['as' => 'topmanagement.purchase.delete', 'uses' => 'TopmanagementController@PurchaseDestroy'])->where(['id' => '[0-9]+']);

        Route::put('/sale', ['as' => 'topmanagement.sale.update', 'uses' => 'TopmanagementController@SaleUpdate']);

        Route::delete('/sale/{id}', ['as' => 'topmanagement.sale.delete', 'uses' => 'TopmanagementController@SaleDestroy'])->where(['id' => '[0-9]+']);


    });


    // reports area===============


});






//Midmanagement Route

Route::group(['prefix' => 'midmanagement-panel', 'middleware' => 'web'], function () {


    Route::get('/test', ['as' => 'midmanagement.test', 'uses' => 'MidmanagementController@Test']);
    Route::get('/dashboard', ['as' => 'midmanagement.dashboard', 'uses' => 'MidmanagementController@DashboardView']);




    // Route for midmanagement=========
    Route::get('/midmanagement', ['as' => 'midmanagement.midmanagement', 'uses' => 'MidmanagementController@MidmanagementView']);
    //Route::post('/midmanagement', ['as'=>'midmanagement.midmanagement.store','uses'=>'MidmanagementController@MidmanagementViewStore']);
    Route::put('/midmanagement', ['as' => 'midmanagement.midmanagement.update', 'uses' => 'MidmanagementController@MidmanagementUpdate']);
    Route::put('/updatePassword', ['as' => 'midmanagement.midmanagement.updatePassword', 'uses' => 'MidmanagementController@UpdatePassword']);
    // Route for midmanagement=========



    // wcheck area===============

    Route::group(['prefix' => 'wcheck'], function () {

        Route::get('/wcheckProduct', ['as' => 'midmanagement.wcheckProduct', 'uses' => 'MidmanagementController@WcheckProductView']);
        Route::post('/wcheckProduct', ['as' => 'midmanagement.wcheckProduct.store', 'uses' => 'MidmanagementController@WcheckProductViewStore']);

        Route::put('/wcheckProduct/repalce', ['as' => 'midmanagement.wcheckProduct.repalce', 'uses' => 'MidmanagementController@WcheckProductReplace']);


    });

    // wcheck area===============




    Route::group(['prefix' => 'reports'], function () {
        Route::get('/dailyRetailerStockReportForDistrict', ['as' => 'midmanagement.dailyRetailerStockReportForDistrict', 'uses' => 'MidmanagementController@DailyRetailerStockReportForDistrictView']);
        Route::post('/dailyRetailerStockReportForDistrict', ['as' => 'midmanagement.dailyRetailerStockReportForDistrict.store', 'uses' => 'MidmanagementController@DailyRetailerStockReportForDistrictViewStore']);
    });


    // reports area===============

    Route::group(['prefix' => 'reports'], function () {

        Route::get('/dailySalesReport', ['as' => 'midmanagement.dailySalesReport', 'uses' => 'MidmanagementController@DailySalesReportView']);
        Route::post('/dailySalesReport', ['as' => 'midmanagement.dailySalesReport.store', 'uses' => 'MidmanagementController@DailySalesReportViewStore']);
        Route::get('/dailySalesReport/print/{user_id?}/{fdstart?}/{fdend?}', ['as' => 'midmanagement.dailySalesReport.print', 'uses' => 'MidmanagementController@DailySalesReportViewPrint']);
    });


    Route::group(['prefix' => 'reports'], function () {

        Route::get('/dailyCampaignReport', ['as' => 'midmanagement.dailyCampaignReport', 'uses' => 'MidmanagementController@DailyCampaignReportView']);
        Route::post('/dailyCampaignReport', ['as' => 'midmanagement.dailyCampaignReport.store', 'uses' => 'MidmanagementController@DailyCampaignReportViewStore']);
        Route::get('/dailyCampaignReport/print/{user_id?}/{fdstart?}/{fdend?}', ['as' => 'midmanagement.dailyCampaignReport.print', 'uses' => 'MidmanagementController@DailyCampaignReportViewPrint']);
    });


    Route::group(['prefix' => 'reports'], function () {

        Route::get('/dailyCampaignReportWithDistrict', ['as' => 'midmanagement.dailyCampaignReportWithDistrict', 'uses' => 'MidmanagementController@DailyCampaignReportWithDistrictView']);
        Route::post('/dailyCampaignReportWithDistrict', ['as' => 'midmanagement.dailyCampaignReportWithDistrict.store', 'uses' => 'MidmanagementController@DailyCampaignReportWithDistrictViewStore']);
    });


    Route::group(['prefix' => 'reports'], function () {

        Route::get('/dailySalesReportWithDistrict', ['as' => 'midmanagement.dailySalesReportWithDistrict', 'uses' => 'MidmanagementController@DailySalesReportWithDistrictView']);

        Route::post('/dailySalesReportWithDistrict', ['as' => 'midmanagement.dailySalesReportWithDistrict.store', 'uses' => 'MidmanagementController@DailySalesReportWithDistrictViewStore']);

        Route::get('/dailySalesReportWithDistrict/print/{user_id?}/{fdstart?}/{fdend?}', ['as' => 'midmanagement.dailySalesReportWithDistrict.print', 'uses' => 'MidmanagementController@DailySalesReportWithDistrictViewPrint']);

    });


    // reports area===============


});


//Midmanagement Route





//Distributor Route

Route::group(['prefix' => 'distributor-panel', 'middleware' => 'web'], function () {

    //-------------------ajax-------------
    Route::group(['prefix' => 'ajaxcode'], function () {
        Route::get('/varifyserialno/{no?}', ['as' => 'ajax.varifyserialno', 'uses' => 'DistributorController@varifyserialno']);

        Route::get('/varifyserialnoTwo/{id?}/{no?}', ['as' => 'ajax.varifyserialnoTwo', 'uses' => 'DistributorController@varifyserialnoTwo']);

        Route::get('/varifyserialnoOne/{no?}', ['as' => 'ajax.varifyserialnoOne', 'uses' => 'RetailerController@varifyserialnoOne']);

    });

    Route::get('/upload1', ['as' => 'distributor.upload1', 'uses' => 'DistributorController@Upload1View']);
    Route::post('/upload1', ['as' => 'distributor.upload1.store', 'uses' => 'DistributorController@Upload1ViewStore']);

    //-------------------ajax-------------

    Route::get('/test', ['as' => 'distributor.test', 'uses' => 'DistributorController@Test']);
    Route::get('/order', ['as' => 'distributor.order', 'uses' => 'DistributorOrderController@DashView']);
    Route::post('/order/store', ['as' => 'distributor.store', 'uses' => 'DistributorOrderController@Store']);
    Route::get('/order/{order_no}', ['as' => 'distributor.details', 'uses' => 'DistributorOrderController@ShowOrder']);
    Route::get('/order_create', ['as' => 'distributor.create', 'uses' => 'DistributorOrderController@Create']);
    Route::get('/dashboard', ['as' => 'distributor.dashboard', 'uses' => 'DistributorController@DashboardView']);
    Route::DELETE('/order/{order_no}', ['as' => 'distributor.destroy', 'uses' => 'DistributorOrderController@destroy']);
    Route::get('/print/{orader_no}', ['as' => 'distributor.print', 'uses' => 'DistributorOrderController@printOrder']);
    Route::post('/oraposting', ['as' => 'oraderposting.store', 'uses' => 'OrderspostingdetailController@store']);
    Route::get('printinvoice/{id}', ['as' => 'distributor.printinvoice', 'uses' => 'WarehouseController@printinvoice']);





    // Route for distributor=========
    Route::get('/distributor', ['as' => 'distributor.distributor', 'uses' => 'DistributorController@DistributorView']);
    //Route::post('/distributor', ['as'=>'distributor.distributor.store','uses'=>'DistributorController@DistributorViewStore']);
    Route::put('/distributor', ['as' => 'distributor.distributor.update', 'uses' => 'DistributorController@DistributorUpdate']);
    Route::put('/updatePassword', ['as' => 'distributor.distributor.updatePassword', 'uses' => 'DistributorController@UpdatePassword']);
    Route::put('/updateAlternativeEmail', ['as' => 'distributor.distributor.updateAlternativeEmail', 'uses' => 'DistributorController@UpdateAlternativeEmail']);
    // Route for distributor=========



    // return area===============

    Route::group(['prefix' => 'return'], function () {

        Route::get('/returnProduct', ['as' => 'distributor.returnProduct', 'uses' => 'DistributorController@ReturnProductView']);
        Route::post('/returnProduct', ['as' => 'distributor.returnProduct.store', 'uses' => 'DistributorController@ReturnProductViewStore']);
        Route::put('/returnProduct', ['as' => 'distributor.returnProduct.update', 'uses' => 'DistributorController@ReturnProductUpdate']);
        Route::delete('/returnProduct/{id}', ['as' => 'distributor.returnProduct.delete', 'uses' => 'DistributorController@ReturnProductDelete'])->where(['id' => '[0-9]+']);


        Route::get('/returndProduct', ['as' => 'distributor.returndProduct', 'uses' => 'DistributorController@ReturndProductView']);
        Route::post('/returndProduct', ['as' => 'distributor.returndProduct.store', 'uses' => 'DistributorController@ReturndProductViewStore']);
        Route::put('/returndProduct', ['as' => 'distributor.returndProduct.update', 'uses' => 'DistributorController@ReturndProductUpdate']);
        Route::delete('/returndProduct/{id}', ['as' => 'distributor.returndProduct.delete', 'uses' => 'DistributorController@ReturndProductDelete'])->where(['id' => '[0-9]+']);

        Route::get('/sreturnProduct', ['as' => 'distributor.sreturnProduct', 'uses' => 'DistributorController@SReturnProductView']);
        Route::post('/sreturnProduct', ['as' => 'distributor.sreturnProduct.store', 'uses' => 'DistributorController@SReturnProductViewStore']);
        Route::put('/sreturnProduct', ['as' => 'distributor.sreturnProduct.update', 'uses' => 'DistributorController@SReturnProductUpdate']);
        Route::delete('/sreturnProduct/{id}', ['as' => 'distributor.sreturnProduct.delete', 'uses' => 'DistributorController@SReturnProductDelete'])->where(['id' => '[0-9]+']);


    });

    // return area===============

    // demand area distributor===============

    Route::group(['prefix' => 'demand'], function () {

        // Route for retailer=========
        Route::get('/retailerEdit', ['as' => 'distributor.retailerEdit', 'uses' => 'DistributorController@retailerEdit']);
        Route::post('/retailerEditUpdate', ['as' => 'distributor.retailerEditUpdate', 'uses' => 'DistributorController@retailerEditUpdate']);

        Route::get('/retailer', ['as' => 'distributor.retailer', 'uses' => 'DistributorController@RetailerView']);
        Route::post('/retailer', ['as' => 'distributor.retailer.store', 'uses' => 'DistributorController@RetailerViewStore']);
        Route::put('/retailer', ['as' => 'distributor.retailer.update', 'uses' => 'DistributorController@RetailerUpdate']);
        Route::delete('/retailer/{id}', ['as' => 'distributor.retailer.delete', 'uses' => 'DistributorController@RetailerDestroy'])->where(['id' => '[0-9]+']);

        // Route for retailer=========
        // Route for SR Starts
        Route::get('/sr', ['as' => 'distributor.sr', 'uses' => 'DistributorController@SRView']);
        Route::post('/sr', ['as' => 'distributor.SRStore', 'uses' => 'DistributorController@SRStore']);
        Route::put('/sr', ['as' => 'distributor.SRUpdate', 'uses' => 'DistributorController@SRUpdate']);
        Route::delete('/sr/{id}', ['as' => 'distributor.SRDelete', 'uses' => 'DistributorController@SRDestroy']);



        // Route for SR Ends

    });

    // demand area===============



    // wcheck area===============

    Route::group(['prefix' => 'wcheck'], function () {

        Route::get('/wcheckProduct', ['as' => 'distributor.wcheckProduct', 'uses' => 'DistributorController@WcheckProductView']);
        Route::post('/wcheckProduct', ['as' => 'distributor.wcheckProduct.store', 'uses' => 'DistributorController@WcheckProductViewStore']);

        Route::put('/wcheckProduct/service', ['as' => 'distributor.wcheckProduct.service', 'uses' => 'DistributorController@WcheckProductService']);

        Route::put('/wcheckProduct/repalce', ['as' => 'distributor.wcheckProduct.repalce', 'uses' => 'DistributorController@WcheckProductReplace']);
        Route::put('/wcheckProduct/doa', ['as' => 'distributor.doaProduct.service', 'uses' => 'DistributorController@DoaProductService']);
        Route::get('/doaProduct', ['as' => 'distributor.doaProduct', 'uses' => 'DistributorController@DoaProductView']);
        Route::post('/doaProduct', ['as' => 'distributor.doaProduct.store', 'uses' => 'DistributorController@DoaProductViewStore']);



    });

    // wcheck area===============


    // reports area===============



    Route::group(['prefix' => 'reports'], function () {
        Route::get('/dailyRetailerStockReportForRetailer', ['as' => 'distributor.dailyRetailerStockReportForRetailer', 'uses' => 'DistributorController@DailyRetailerStockReportForRetailerView']);
        Route::post('/dailyRetailerStockReportForRetailer', ['as' => 'distributor.dailyRetailerStockReportForRetailer.store', 'uses' => 'DistributorController@DailyRetailerStockReportForRetailerViewStore']);
    });




    Route::group(['prefix' => 'reports'], function () {

        Route::get('/dailySalesReport', ['as' => 'distributor.dailySalesReport', 'uses' => 'DistributorController@DailySalesReportView']);
        Route::post('/dailySalesReport', ['as' => 'distributor.dailySalesReport.store', 'uses' => 'DistributorController@DailySalesReportViewStore']);
        Route::get('/dailySalesReport/print/{user_id?}/{fdstart?}/{fdend?}', ['as' => 'distributor.dailySalesReport.print', 'uses' => 'DistributorController@DailySalesReportViewPrint']);


        Route::get('/distributorImeiStockReport', ['as' => 'distributor.distributorImeiStockReport', 'uses' => 'DistributorController@DistributorImeiStockReportView']);

        Route::get('/retailerImeiStockReport', ['as' => 'distributor.retailerImeiStockReport', 'uses' => 'DistributorController@RetailerImeiStockReportView']);



        Route::get('/dailyCampaignReport', ['as' => 'distributor.dailyCampaignReport', 'uses' => 'DistributorController@DailyCampaignReportView']);
        Route::post('/dailyCampaignReport', ['as' => 'distributor.dailyCampaignReport.store', 'uses' => 'DistributorController@DailyCampaignReportViewStore']);
        Route::get('/dailyCampaignReport/print/{user_id?}/{fdstart?}/{fdend?}', ['as' => 'distributor.dailyCampaignReport.print', 'uses' => 'DistributorController@DailyCampaignReportViewPrint']);



        Route::get('/dailyStockReport', ['as' => 'distributor.dailyStockReport', 'uses' => 'DistributorController@DailyStockReportView']);
        Route::post('/dailyStockReport', ['as' => 'distributor.dailyStockReport.store', 'uses' => 'DistributorController@DailyStockReportViewStore']);
        //Route::get('/dailyStockReport/print/{user_id?}/{fdstart?}/{fdend?}', ['as'=>'distributor.dailyStockReport.print','uses'=>'DistributorController@DailyStockReportViewPrint']);


        Route::get('/dailyPurchaseReport', ['as' => 'distributor.dailyPurchaseReport', 'uses' => 'DistributorController@DailyPurchaseReportView']);
        Route::post('/dailyPurchaseReport', ['as' => 'distributor.dailyPurchaseReport.store', 'uses' => 'DistributorController@DailyPurchaseReportViewStore']);

        //Download Purchase Data from Distributor
        Route::get('/purchaseCurrentMonthExcel', ['as' => 'purchaseCurrentMonthExcel', 'uses' => 'DistributorController@purchaseCurrentMonthExcel']);
        Route::get('/purchaseSixMonthExcel', ['as' => 'purchaseSixMonthExcel', 'uses' => 'DistributorController@purchaseSixMonthExcel']);

        //Download Sale Data from Distributor
        Route::get('/saleCurrentMonthExcel', ['as' => 'saleCurrentMonthExcel', 'uses' => 'DistributorController@saleCurrentMonthExcel']);
        Route::get('/saleSixMonthExcel', ['as' => 'saleSixMonthExcel', 'uses' => 'DistributorController@saleSixMonthExcel']);

        Route::get('/dailyPurchaseApprove', ['as' => 'distributor.dailyPurchaseApprove', 'uses' => 'DistributorController@DailyPurchaseApproveView']);
        Route::post('/dailyPurchaseApprove', ['as' => 'distributor.dailyPurchaseApprove.store', 'uses' => 'DistributorController@DailyPurchaseApproveViewStore']);

        Route::post('/dailyPurchaseApproveStatus', ['as' => 'distributor.dailyPurchaseApprove.status', 'uses' => 'DistributorController@DailyPurchaseApproveViewStatus']);


        # ReportV1====

        Route::get('/dailyPurchaseReportV1', ['as' => 'distributor.dailyPurchaseReportV1', 'uses' => 'DistributorController@DailyPurchaseReportV1View']);
        Route::post('/dailyPurchaseReportV1', ['as' => 'distributor.dailyPurchaseReportV1.store', 'uses' => 'DistributorController@DailyPurchaseReportV1ViewStore']);

        Route::get('/dailySalesReportV1', ['as' => 'distributor.dailySalesReportV1', 'uses' => 'DistributorController@DailySalesReportV1View']);
        Route::post('/dailySalesReportV1', ['as' => 'distributor.dailySalesReportV1.store', 'uses' => 'DistributorController@DailySalesReportV1ViewStore']);

        Route::get('/dailyStockReportV1', ['as' => 'distributor.dailyStockReportV1', 'uses' => 'DistributorController@DailyStockReportV1View']);
        Route::post('/dailyStockReportV1', ['as' => 'distributor.dailyStockReportV1.store', 'uses' => 'DistributorController@DailyStockReportV1ViewStore']);


        Route::get('/dailyRtlStockReportV1', ['as' => 'distributor.dailyRtlStockReportV1', 'uses' => 'DistributorController@DailyRtlStockReportV1View']);
        Route::post('/dailyRtlStockReportV1', ['as' => 'distributor.dailyRtlStockReportV1.store', 'uses' => 'DistributorController@DailyRtlStockReportV1ViewStore']);


        Route::get('/retailerdwnld', ['as' => 'distributor.retailerdwnld', 'uses' => 'DistributorController@RetailerdwnldView']);
        Route::post('/retailerdwnld', ['as' => 'distributor.retailerdwnld.store', 'uses' => 'DistributorController@RetailerdwnldViewStore']);


        Route::get('/dailyReplaceReport', ['as' => 'distributor.dailyReplaceReport', 'uses' => 'DistributorController@DailyReplaceReportView']);
        Route::post('/dailyReplaceReport', ['as' => 'distributor.dailyReplaceReport.store', 'uses' => 'DistributorController@DailyReplaceReportViewStore']);
        Route::get('/dailyReplaceReport/print/{user_id?}/{fdstart?}/{fdend?}', ['as' => 'distributor.dailyReplaceReport.print', 'uses' => 'DistributorController@DailyReplaceReportViewPrint']);



        Route::get('/terExcel', ['as' => 'distributor.stock.terexcel', 'uses' => 'DistributorController@TerViewExcel']);
        # ReportV1====

    });


    // reports area===============




    // purchase area===============

    Route::group(['prefix' => 'purchase'], function () {

        Route::get('/purchase', ['as' => 'distributor.purchase', 'uses' => 'DistributorController@PurchaseView']);
        Route::post('/purchase', ['as' => 'distributor.purchase.store', 'uses' => 'DistributorController@PurchaseViewStore']);

        Route::put('/purchase', ['as' => 'distributor.purchase.update', 'uses' => 'DistributorController@PurchaseUpdate']);


        Route::delete('/purchase/{id}', ['as' => 'distributor.purchase.delete', 'uses' => 'DistributorController@PurchaseDestroy'])->where(['id' => '[0-9]+']);

    });

    // purchase area===============

    // sale area===============

    Route::group(['prefix' => 'sale'], function () {

        Route::get('/sale', ['as' => 'distributor.sale', 'uses' => 'DistributorController@SaleView']);
        Route::post('/sale', ['as' => 'distributor.sale.store', 'uses' => 'DistributorController@SaleViewStore']);
        Route::put('/sale', ['as' => 'distributor.sale.update', 'uses' => 'DistributorController@SaleUpdate']);
        Route::put('/sale', ['as' => 'distributor.sale.return.update', 'uses' => 'DistributorController@SaleReturnUpdate']);

        Route::delete('/sale/{id}', ['as' => 'distributor.sale.delete', 'uses' => 'DistributorController@SaleDestroy'])->where(['id' => '[0-9]+']);

    });

    // sale area===============





});







//Retailer Route

Route::group(['prefix' => 'retailer-panel', 'middleware' => 'web'], function () {


    Route::get('/test', ['as' => 'retailer.test', 'uses' => 'RetailerController@Test']);
    Route::get('/dashboard', ['as' => 'retailer.dashboard', 'uses' => 'RetailerController@DashboardView']);



    // Route for retailer=========
    Route::get('/retailer', ['as' => 'retailer.retailer', 'uses' => 'RetailerController@RetailerView']);
    //Route::post('/retailer', ['as'=>'retailer.retailer.store','uses'=>'RetailerController@RetailerViewStore']);
    Route::put('/retailer', ['as' => 'retailer.retailer.update', 'uses' => 'RetailerController@RetailerUpdate']);
    Route::put('/updatePassword', ['as' => 'retailer.retailer.updatePassword', 'uses' => 'RetailerController@UpdatePassword']);
    // Route for retailer=========



    // return area===============

    Route::group(['prefix' => 'return'], function () {

        Route::get('/returnProduct', ['as' => 'retailer.returnProduct', 'uses' => 'RetailerController@ReturnProductView']);
        Route::post('/returnProduct', ['as' => 'retailer.returnProduct.store', 'uses' => 'RetailerController@ReturnProductViewStore']);
        Route::put('/returnProduct', ['as' => 'retailer.returnProduct.update', 'uses' => 'RetailerController@ReturnProductUpdate']);
        Route::delete('/returnProduct/{id}', ['as' => 'retailer.returnProduct.delete', 'uses' => 'RetailerController@ReturnProductDelete'])->where(['id' => '[0-9]+']);
    });

    // return area===============

    // dontwarry area===============

    Route::group(['prefix' => 'dontwarry'], function () {

        Route::get('/dontWorry', ['as' => 'retailer.dontWorry', 'uses' => 'RetailerController@DontWorryView']);
        Route::post('/dontWorry', ['as' => 'retailer.dontWorry.store', 'uses' => 'RetailerController@DontWorryViewStore']);
        Route::put('/dontWorry', ['as' => 'retailer.dontWorry.update', 'uses' => 'RetailerController@DontWorryUpdate']);
        Route::delete('/dontWorry/{id}', ['as' => 'retailer.dontWorry.delete', 'uses' => 'RetailerController@DontWorryDelete'])->where(['id' => '[0-9]+']);
    });

    // dontwarry area===============






    // wcheck area===============

    Route::group(['prefix' => 'wcheck'], function () {

        Route::get('/wcheckProduct', ['as' => 'retailer.wcheckProduct', 'uses' => 'RetailerController@WcheckProductView']);
        Route::post('/wcheckProduct', ['as' => 'retailer.wcheckProduct.store', 'uses' => 'RetailerController@WcheckProductViewStore']);

        Route::put('/wcheckProduct/service', ['as' => 'retailer.wcheckProduct.service', 'uses' => 'RetailerController@WcheckProductService']);

        Route::put('/wcheckProduct/repalce', ['as' => 'retailer.wcheckProduct.repalce', 'uses' => 'RetailerController@WcheckProductReplace']);


    });

    // wcheck area===============


    // reports area===============

    Route::group(['prefix' => 'reports'], function () {

        Route::get('/dailySalesReport', ['as' => 'retailer.dailySalesReport', 'uses' => 'RetailerController@DailySalesReportView']);
        Route::post('/dailySalesReport', ['as' => 'retailer.dailySalesReport.store', 'uses' => 'RetailerController@DailySalesReportViewStore']);
        Route::get('/dailySalesReport/print/{user_id?}/{fdstart?}/{fdend?}', ['as' => 'retailer.dailySalesReport.print', 'uses' => 'RetailerController@DailySalesReportViewPrint']);
    });


    Route::group(['prefix' => 'reports'], function () {

        Route::get('/dailyCampaignReport', ['as' => 'retailer.dailyCampaignReport', 'uses' => 'RetailerController@DailyCampaignReportView']);
        Route::post('/dailyCampaignReport', ['as' => 'retailer.dailyCampaignReport.store', 'uses' => 'RetailerController@DailyCampaignReportViewStore']);
        Route::get('/dailyCampaignReport/print/{user_id?}/{fdstart?}/{fdend?}', ['as' => 'retailer.dailyCampaignReport.print', 'uses' => 'RetailerController@DailyCampaignReportViewPrint']);
    });


    // reports area===============


});









//Sales Route

Route::group(['prefix' => 'sales-panel', 'middleware' => 'web'], function () {
    Route::get('/test', ['as' => 'sales.test', 'uses' => 'SalesController@Test']);
    Route::get('/dashboard', ['as' => 'sales.dashboard', 'uses' => 'SalesController@DashboardView']);

});



//Tso Route

Route::group(['prefix' => 'tso-panel', 'middleware' => 'web'], function () {

    //Route::resource('oraderposting', 'OrderspostingdetailController');
    Route::post('/oraposting', ['as' => 'oraderposting.store', 'uses' => 'OrderspostingdetailController@store']);
    //  Route::get('/oraderlist', ['as'=>'paindig.oraderlist','uses'=>'OrderspostingController@index']);


    //  Route::get('/orderspostings', ['as'=>'orderspostings.destroy','uses'=>'OrderspostingController@index']);
    //  Route::get('/orderspostings', ['as'=>' orderspostings.show','uses'=>'OrderspostingController@show']);
    //  Route::get('/orderspostings', ['as'=>'orderspostings.edit','uses'=>'OrderspostingController@edit']);




    Route::get('/test', ['as' => 'tso.test', 'uses' => 'TsoController@Test']);
    Route::get('/dashboard', ['as' => 'tso.dashboard', 'uses' => 'TsoController@DashboardView']);

    Route::get('/orders', ['as' => 'tso.orader', 'uses' => 'OrderController@tsoOrderList']);
    Route::get('/print/{orader_no}', ['as' => 'tsorder.print', 'uses' => 'OrderController@printOrder']);
    Route::get('/order/create', ['as' => 'tso.create', 'uses' => 'OrderController@tsoOrderCreate']);
    Route::post('/orader/store', ['as' => 'tso.store', 'uses' => 'OrderController@tsoOrderStore']);
    Route::get('/orader/{orader_no}', ['as' => 'tso.details', 'uses' => 'OrderController@tsoOrderDetails']);
    Route::DELETE('/orader/{orader_no}', ['as' => 'tso.destroy', 'uses' => 'OrderController@tsoOrderDestroy']);


    // Route for tso=========
    Route::get('/tso', ['as' => 'tso.tso', 'uses' => 'TsoController@TsoView']);
    //Route::post('/tso', ['as'=>'tso.tso.store','uses'=>'TsoController@TsoViewStore']);
    Route::put('/tso', ['as' => 'tso.tso.update', 'uses' => 'TsoController@TsoUpdate']);
    Route::put('/updatePassword', ['as' => 'tso.tso.updatePassword', 'uses' => 'TsoController@UpdatePassword']);
    // Route for tso=========



    // wcheck area===============

    Route::group(['prefix' => 'wcheck'], function () {

        Route::get('/wcheckProduct', ['as' => 'tso.wcheckProduct', 'uses' => 'TsoController@WcheckProductView']);
        Route::post('/wcheckProduct', ['as' => 'tso.wcheckProduct.store', 'uses' => 'TsoController@WcheckProductViewStore']);

        Route::put('/wcheckProduct/repalce', ['as' => 'tso.wcheckProduct.repalce', 'uses' => 'TsoController@WcheckProductReplace']);


    });

    // wcheck area===============



    // demand area===============

    Route::group(['prefix' => 'demand'], function () {

        // Route for retailer=========

        Route::get('/retailer', ['as' => 'tso.retailer', 'uses' => 'TsoController@RetailerView']);
        Route::post('/retailer', ['as' => 'tso.retailer.store', 'uses' => 'TsoController@RetailerViewStore']);
        Route::put('/retailer', ['as' => 'tso.retailer.update', 'uses' => 'TsoController@RetailerUpdate']);
        Route::delete('/retailer/{id}', ['as' => 'tso.retailer.delete', 'uses' => 'TsoController@RetailerDestroy'])->where(['id' => '[0-9]+']);

        // Route for retailer=========

    });

    // demand area===============



    // reports area===============



    Route::group(['prefix' => 'reports'], function () {
        Route::get('/dailyRetailerStockReportForUpazila', ['as' => 'tso.dailyRetailerStockReportForUpazila', 'uses' => 'TsoController@DailyRetailerStockReportForUpazilaView']);
        Route::post('/dailyRetailerStockReportForUpazila', ['as' => 'tso.dailyRetailerStockReportForUpazila.store', 'uses' => 'TsoController@DailyRetailerStockReportForUpazilaViewStore']);
    });


    Route::group(['prefix' => 'reports'], function () {
        Route::get('/dailyRetailerStockReportForUpazila', ['as' => 'tso.dailyRetailerStockReportForUpazila', 'uses' => 'TsoController@DailyRetailerStockReportForUpazilaView']);
        Route::post('/dailyRetailerStockReportForUpazila', ['as' => 'tso.dailyRetailerStockReportForUpazila.store', 'uses' => 'TsoController@DailyRetailerStockReportForUpazilaViewStore']);
    });



    Route::group(['prefix' => 'reports'], function () {

        Route::get('/dailySalesReport', ['as' => 'tso.dailySalesReport', 'uses' => 'TsoController@DailySalesReportView']);
        Route::post('/dailySalesReport', ['as' => 'tso.dailySalesReport.store', 'uses' => 'TsoController@DailySalesReportViewStore']);
        Route::get('/dailySalesReport/print/{user_id?}/{fdstart?}/{fdend?}', ['as' => 'tso.dailySalesReport.print', 'uses' => 'TsoController@DailySalesReportViewPrint']);
    });


    Route::group(['prefix' => 'reports'], function () {
        Route::get('/dailyPurchaseReports', ['as' => 'tso.dailyPurchaseReports', 'uses' => 'TsoController@DailyPurchaseReportView']);
        Route::post('/dailyPurchaseReports', ['as' => 'tso.dailyPurchaseReports.store', 'uses' => 'TsoController@DailyPurchaseReportViewStore']);


        Route::get('/dailySalesReports', ['as' => 'tso.dailySalesReports', 'uses' => 'TsoController@DailySalesReportViews']);
        Route::post('/dailySalesReports', ['as' => 'tso.dailySalesReports.store', 'uses' => 'TsoController@DailySalesReportViewsStore']);

        Route::get('/distributorImeiStockReport', ['as' => 'tso.distributorImeiStockReport', 'uses' => 'TsoController@DistributorImeiStockReportView']);

        Route::get('/retailerImeiStockReport', ['as' => 'tso.retailerImeiStockReport', 'uses' => 'TsoController@RetailerImeiStockReportView']);

        Route::get('/wod', ['as' => 'tso.wod', 'uses' => 'TsoController@WodView']);
        Route::post('/wod', ['as' => 'tso.wod.store', 'uses' => 'TsoController@WodViewStore']);

    });

    Route::group(['prefix' => 'reports'], function () {

        Route::get('/dailyCampaignReport', ['as' => 'tso.dailyCampaignReport', 'uses' => 'TsoController@DailyCampaignReportView']);
        Route::post('/dailyCampaignReport', ['as' => 'tso.dailyCampaignReport.store', 'uses' => 'TsoController@DailyCampaignReportViewStore']);
        Route::get('/dailyCampaignReport/print/{user_id?}/{fdstart?}/{fdend?}', ['as' => 'tso.dailyCampaignReport.print', 'uses' => 'TsoController@DailyCampaignReportViewPrint']);
    });




    Route::group(['prefix' => 'reports'], function () {

        Route::get('/dailyCampaignReportWithUpazila', ['as' => 'tso.dailyCampaignReportWithUpazila', 'uses' => 'TsoController@DailyCampaignReportWithUpazilaView']);
        Route::post('/dailyCampaignReportWithUpazila', ['as' => 'tso.dailyCampaignReportWithUpazila.store', 'uses' => 'TsoController@DailyCampaignReportWithUpazilaViewStore']);
    });


    Route::group(['prefix' => 'reports'], function () {

        Route::get('/dailySalesReportWithUpazila', ['as' => 'tso.dailySalesReportWithUpazila', 'uses' => 'TsoController@DailySalesReportWithUpazilaView']);

        Route::post('/dailySalesReportWithUpazila', ['as' => 'tso.dailySalesReportWithUpazila.store', 'uses' => 'TsoController@DailySalesReportWithUpazilaViewStore']);



    });


    // reports area===============


});

//Tso Route



//Huawei Route

Route::group(['prefix' => 'huawei-panel', 'middleware' => 'web'], function () {


    Route::get('/test', ['as' => 'huawei.test', 'uses' => 'HuaweiController@Test']);
    Route::get('/dashboard', ['as' => 'huawei.dashboard', 'uses' => 'HuaweiController@DashboardView']);



    // Route for huawei=========
    Route::get('/huawei', ['as' => 'huawei.huawei', 'uses' => 'HuaweiController@HuaweiView']);
    //Route::post('/huawei', ['as'=>'huawei.huawei.store','uses'=>'HuaweiController@HuaweiViewStore']);
    Route::put('/huawei', ['as' => 'huawei.huawei.update', 'uses' => 'HuaweiController@HuaweiUpdate']);
    Route::put('/updatePassword', ['as' => 'huawei.huawei.updatePassword', 'uses' => 'HuaweiController@UpdatePassword']);
    // Route for huawei=========



    // wcheck area===============

    Route::group(['prefix' => 'wcheck'], function () {

        Route::get('/wcheckProduct', ['as' => 'huawei.wcheckProduct', 'uses' => 'HuaweiController@WcheckProductView']);
        Route::post('/wcheckProduct', ['as' => 'huawei.wcheckProduct.store', 'uses' => 'HuaweiController@WcheckProductViewStore']);

        Route::put('/wcheckProduct/repalce', ['as' => 'huawei.wcheckProduct.repalce', 'uses' => 'HuaweiController@WcheckProductReplace']);


    });

    // wcheck area===============



    // active area===============

    Route::group(['prefix' => 'active'], function () {

        Route::get('/activewarranty', ['as' => 'admin.activewarranty', 'uses' => 'WarrantyActivationController@activeWarranty']);
        Route::post('/activewarranty', ['as' => 'admin.activewarranty.store', 'uses' => 'WarrantyActivationController@activeWarrantyStore']);
    });

    // active area===============


    // return area===============
    Route::group(['prefix' => 'return'], function () {
        Route::get('/returnProductAll', ['as' => 'huawei.returnProductAll', 'uses' => 'HuaweiController@ReturnProductViewAll']);

    });
    // return area===============



    // reports area===============

    Route::group(['prefix' => 'reports'], function () {

        Route::get('/dailyReturnReport', ['as' => 'huawei.dailyReturnReport', 'uses' => 'HuaweiController@DailyReturnReportView']);
        Route::post('/dailyReturnReport', ['as' => 'huawei.dailyReturnReport.store', 'uses' => 'HuaweiController@DailyReturnReportViewStore']);

    });



    Route::group(['prefix' => 'reports'], function () {

        Route::get('/dailyCampaignReportWithDistrict', ['as' => 'huawei.dailyCampaignReportWithDistrict', 'uses' => 'HuaweiController@DailyCampaignReportWithDistrictView']);
        Route::post('/dailyCampaignReportWithDistrict', ['as' => 'huawei.dailyCampaignReportWithDistrict.store', 'uses' => 'HuaweiController@DailyCampaignReportWithDistrictViewStore']);
    });


    Route::group(['prefix' => 'reports'], function () {

        Route::get('/dailySalesReportWithDistrict', ['as' => 'huawei.dailySalesReportWithDistrict', 'uses' => 'HuaweiController@DailySalesReportWithDistrictView']);

        Route::post('/dailySalesReportWithDistrict', ['as' => 'huawei.dailySalesReportWithDistrict.store', 'uses' => 'HuaweiController@DailySalesReportWithDistrictViewStore']);

    });



    Route::group(['prefix' => 'reports'], function () {

        Route::get('/dailySalesReport', ['as' => 'huawei.dailySalesReport', 'uses' => 'HuaweiController@DailySalesReportView']);
        Route::post('/dailySalesReport', ['as' => 'huawei.dailySalesReport.store', 'uses' => 'HuaweiController@DailySalesReportViewStore']);
    });


    Route::group(['prefix' => 'reports'], function () {

        Route::get('/dailyCampaignReport', ['as' => 'huawei.dailyCampaignReport', 'uses' => 'HuaweiController@DailyCampaignReportView']);
        Route::post('/dailyCampaignReport', ['as' => 'huawei.dailyCampaignReport.store', 'uses' => 'HuaweiController@DailyCampaignReportViewStore']);
    });


    Route::group(['prefix' => 'reports'], function () {

        Route::get('/dailyStockReport', ['as' => 'huawei.dailyStockReport', 'uses' => 'HuaweiController@DailyStockReportView']);
        Route::post('/dailyStockReport', ['as' => 'huawei.dailyStockReport.store', 'uses' => 'HuaweiController@DailyStockReportViewStore']);
    });


    Route::group(['prefix' => 'reports'], function () {

        Route::get('/dailyPurchaseSaleReport', ['as' => 'huawei.dailyPurchaseSaleReport', 'uses' => 'HuaweiController@DailyPurchaseSaleReportView']);
        Route::post('/dailyPurchaseSaleReport', ['as' => 'huawei.dailyPurchaseSaleReport.store', 'uses' => 'HuaweiController@DailyPurchaseSaleReportViewStore']);

    });

    Route::group(['prefix' => 'reports'], function () {

        Route::get('/dailyHuaweiSalesReport', ['as' => 'huawei.dailyDistributorSalesReport', 'uses' => 'HuaweiController@DailyDistributorSalesReportView']);
        Route::post('/dailyHuaweiSalesReport', ['as' => 'huawei.dailyDistributorSalesReport.store', 'uses' => 'HuaweiController@DailyDistributorSalesReportViewStore']);

    });

    Route::group(['prefix' => 'reports'], function () {

        Route::get('/retailerCheckReport', ['as' => 'huawei.retailerCheckReport', 'uses' => 'HuaweiController@RetailerCheckReportView']);
        Route::post('/retailerCheckReport', ['as' => 'huawei.retailerCheckReport.store', 'uses' => 'HuaweiController@RetailerCheckReportViewStore']);




    });


    Route::group(['prefix' => 'reports'], function () {
        Route::get('/dailyRetailerStockReport', ['as' => 'huawei.dailyRetailerStockReport', 'uses' => 'HuaweiController@DailyRetailerStockReportView']);
        Route::post('/dailyRetailerStockReport', ['as' => 'huawei.dailyRetailerStockReport.store', 'uses' => 'HuaweiController@DailyRetailerStockReportViewStore']);
    });


    // reports area===============


});


//Huawei Route

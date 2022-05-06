<?php

use App\Model\Ams\AuthToken;
use App\Model\Ams\CampaignReportDownload;
use App\Model\Ams\Profile;
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
Route::put('/profile/changeMode', 'ProfileController@changeMode')->name('profile.changeMode');
Route::post('/profile/changePassword', 'ProfileController@changePassword')->name('profile.changePassword');
Route::get('/profile/getBrands', 'ProfileController@getBrands')->name('profile.getBrands');
Route::get('/ams/dashboard', 'AmsController@index')->name('ams.dashBoard'); // ams dashboard
Route::get('/ams/code', 'AmsController@code')->name('ams.code'); // return url

Route::get('/ams/cron', 'AMS\CornJobController@index')->name('ams.cron'); // ams cron job list
Route::put('/ams/cron/time/{cron}', 'AMS\CornJobController@update')->name('ams.cron.update'); // ams cron time update
Route::get('/ams/{cron}/edit', 'AMS\CornJobController@edit')->name('ams.cron.edit'); // data for editing the specified ams cron
Route::put('/ams/cron/status/{cron}', 'AMS\CornJobController@updateStatus')->name('ams.cron.status'); // ams cron
Route::get('/ams/report', 'AMS\CornJobController@reportStatus')->name('ams.report'); // ams report status
Route::post('/ams/report/status', 'AMS\CornJobController@getReportSatus')->name('ams.report.status');
Route::post('/ams/report/recover', 'AMS\CornJobController@recoverReport')->name('ams.report.recover');
Route::post('/ams/report/recover/range', 'AMS\CornJobController@recoverReportByRange')->name('ams.report.recover.range');

Route::get('/biddingRule', 'BiddingRule\BiddingRuleController@index')->name('biddingRule');
Route::put('/biddingRule/getPortfolioOrCampaignList', 'BiddingRule\BiddingRuleController@getPortfolioOrCampaignList')->name('biddingRule.getPortfolioOrCampaignList');
Route::get('/biddingRule/getOldRuleList', 'BiddingRule\BiddingRuleController@getOldRuleList')->name('biddingRule.getOldRuleList');
Route::put('/biddingRule/getPreSetPortfolioOrCampaignList', 'BiddingRule\BiddingRuleController@getPreSetPortfolioOrCampaignList')->name('biddingRule.getPreSetPortfolioOrCampaignList');
Route::put('/biddingRule/getEmailList', 'BiddingRule\BiddingRuleController@getEmailList')->name('biddingRule.getEmailList');
Route::post('/biddingRule/storePreSetRule', 'BiddingRule\BiddingRuleController@storePreSetRule')->name('biddingRule.storePreSetRule');
Route::post('/biddingRule/storeRule', 'BiddingRule\BiddingRuleController@storeRule')->name('biddingRule.storeRule');
Route::get('/biddingRule/getPreSetRules', 'BiddingRule\BiddingRuleController@getPreSetRules')->name('biddingRule.getPreSetRules');
Route::put('/biddingRule/getPreSetRuleInfo', 'BiddingRule\BiddingRuleController@getPreSetRuleInfo')->name('biddingRule.getPreSetRuleInfo');
Route::get('/biddingRule/{rule}', 'BiddingRule\BiddingRuleController@show')->name('biddingRule.show');
Route::put('/biddingRule/{rule}', 'BiddingRule\BiddingRuleController@updateRule')->name('biddingRule.updateRule');
Route::delete('/biddingRule/{rule}', 'BiddingRule\BiddingRuleController@destroyRule')->name('biddingRule.destroy');
Route::put('/biddingRule/status/{rule}', 'BiddingRule\BiddingRuleController@setRuleStatus')->name('biddingRule.update.status');

Route::post('/profile/switchBrand', 'ProfileController@switchBrand');

Route::get('notification', 'Alerts\NotificationController@index')->name('notification.index');
Route::get('/notification/new', 'Alerts\NotificationController@getNewNotification')->name('notification.new');
Route::get('/notification/mark/read/all', 'Alerts\NotificationController@markAllAsRead')->name('notification.mark.read.all');
Route::get('/notification/show/{alert}', 'Alerts\NotificationController@show')->name('notification.show');
Route::post('/notification/mark/{alert}/disable', 'Alerts\NotificationController@disable')->name('notification.mark.disable');

Route::get('/home', 'ExecutiveDashboard\ExecutiveDashboard@index')->name('home');
Route::post('ed/report', 'ExecutiveDashboard\ExecutiveDashboard@getEDReport')->name('ed.report');
Route::post('ed/vendor/report', 'ExecutiveDashboard\ExecutiveDashboard@getVendorDetails')->name('ed.vendor.report');
Route::post('ed/vendor/trailing/sc', 'ExecutiveDashboard\ExecutiveDashboard@getShippedCogsTrailing')->name('ed.vendor.trailing.sc');
Route::post('ed/vendor/trailing/nr', 'ExecutiveDashboard\ExecutiveDashboard@getNetReceivedTrailing')->name('ed.vendor.trailing.nr');
Route::post('ed/vendor/trailing/op', 'ExecutiveDashboard\ExecutiveDashboard@getOrderedProductTrailing')->name('ed.vendor.trailing.op');
Route::post('ed/vendor/trailing/roas', 'ExecutiveDashboard\ExecutiveDashboard@getRoasTrailing')->name('ed.vendor.trailing.roas');
Route::post('ed/vendor/store', 'ExecutiveDashboard\ExecutiveDashboard@setEDVendor')->name('ed.vendor.store');
Route::post('ed/get/vendor', 'ExecutiveDashboard\ExecutiveDashboard@getEdVendorForMarketplace')->name('ed.get.vendor');
Route::post('ed/table/sc', 'ExecutiveDashboard\ExecutiveDashboard@getEDTableSC')->name('ed.table.sc');
Route::post('ed/table/nc', 'ExecutiveDashboard\ExecutiveDashboard@getEDTableNC')->name('ed.table.nc');
Route::post('ed/table/all', 'ExecutiveDashboard\ExecutiveDashboard@getAllEdTable')->name('ed.table.all');
Route::post('ed/vendor/trailing', 'ExecutiveDashboard\ExecutiveDashboard@getScNcTrailing')->name('ed.vendor.trailing');

Route::get('ed/confirmPO', 'ExecutiveDashboard\ConfirmPO@index')->name('ed.confirmPO');
Route::post('ed/confirmPO/report', 'ExecutiveDashboard\ConfirmPO@getEDConfirmPOReport')->name('ed.confirmPO.report');
Route::post('ed/confirmPO/report/vendor', 'ExecutiveDashboard\ConfirmPO@getEDConfirmPOVendorReport')->name('ed.confirmPO.report.vendor');
Route::post('ed/confirmPO/vendor/store', 'ExecutiveDashboard\ConfirmPO@setTopEDPOVendor')->name('ed.confirmPO.vendor.store');

Route::get('po/plan', 'ExecutiveDashboard\ConfirmPO@getPOPlan')->name('po.plan');
Route::post('po/plan/store', 'ExecutiveDashboard\ConfirmPO@setPOPlan')->name('po.plan.store');

Route::get('ed/confirmPOExtended', 'ExecutiveDashboard\ConfirmPOExtended@index')->name('ed.confirmPOExtended');
Route::post('ed/confirmPOExtended/report', 'ExecutiveDashboard\ConfirmPOExtended@getEDConfirmPOReport')->name('ed.confirmPOExtended.report');
Route::post('ed/confirmPOExtended/report/vendor', 'ExecutiveDashboard\ConfirmPOExtended@getEDConfirmPOVendorReport')->name('ed.confirmPOExtended.report.vendor');
Route::post('ed/confirmPOExtended/vendor/store', 'ExecutiveDashboard\ConfirmPOExtended@setTopEDPOVendor')->name('ed.confirmPOExtended.vendor.store');

// Route::get('po/plan', 'ExecutiveDashboard\ConfirmPOExtended@getPOPlan')->name('po.plan');
// Route::post('po/plan/store', 'ExecutiveDashboard\ConfirmPOExtended@setPOPlan')->name('po.plan.store');

Route::get('threshold', 'Alerts\ThresholdController@index')->name('threshold');
Route::post('threshold/store', 'Alerts\ThresholdController@store')->name('threshold.store');
Route::delete('threshold/remove/{threshold}', 'Alerts\ThresholdController@destroy')->name('threshold.destroy');


Route::get('superadmin/restore', 'SuperAdminController@restore')->name('superadmin.restore');
Route::resource('/superadmin', 'SuperAdminController');
Route::put('superadmin/status/{user}', 'SuperAdminController@updateStatus');

Route::get('admin/restore', 'AdminController@restore')->name('admin.restore');
Route::resource('/admin', 'AdminController');
Route::put('admin/status/{user}', 'AdminController@updateStatus');

Route::get('operator/restore', 'OperatorController@restore')->name('operator.restore');
Route::resource('/operator', 'OperatorController');
Route::put('operator/status/{user}', 'OperatorController@updateStatus');

Route::get('user/restore', 'UserController@restore')->name('user.restore');
Route::resource('/user', 'UserController');
Route::put('user/status/{user}', 'UserController@updateStatus');

Route::get('user-vendors/restore', 'VendorsController@restore')->name('user-vendors.restore');
Route::resource('/user-vendors', 'VendorsController');
Route::put('user-vendors/status/{user}', 'VendorsController@updateStatus');
Route::get('user-vendors/profiles/{vendor}', 'VendorsController@getAssociatedProfiles')->name('user-vendors.profiles');
Route::get('user-vendors/assignProfile/{profile}', 'VendorsController@getUnassignedProfiles')->name('user-vendors.assignProfile');
Route::put('user-vendors/assignProfile/{vendor}', 'VendorsController@assignProfile')->name('user-vendors.assignProfile');
Route::put('user-vendors/unAssignProfile/{vendor}', 'VendorsController@unAssignProfile')->name('user-vendors.unAssignProfile');

Route::get('brand/restore', 'BrandController@restore')->name('brand.restore');
Route::resource('/brand', 'BrandController');
Route::put('brand/status/{user}', 'BrandController@updateStatus');
Route::get('brand/unassignedUsers/{brand}', 'BrandController@getUnassignedUsers')->name('brand.unassignedUsers');
Route::get('brand/users/{brand}', 'BrandController@getAssignedUsers')->name('brand.users');
Route::put('brand/assign/{user}', 'BrandController@assignUser')->name('brand.assign');
Route::put('brand/unassign/{user}', 'BrandController@unassignUser')->name('brand.unassign');

Route::get('brand/vendors/{brand}', 'BrandController@getAssociatedVendors')->name('brand.vendors');
Route::get('brand/unassignedVendors/{brand}', 'BrandController@getUnassignedVendors')->name('brand.unassignedVendors');
Route::put('brand/assignVendor/{vendor}', 'BrandController@assignVendor')->name('brand.assignVendor');
Route::put('brand/unassignVendor/{vendor}', 'BrandController@unassignVendor')->name('brand.unassignVendor');

Route::get('sales/visual', 'Sales\SalesVisualController@index')->name('sales.visual');
Route::post('sales/visual/graph', 'Sales\SalesVisualController@getSaleGraph')->name('sales.visual.graph');
Route::get('sales/visual/new', 'Sales\NewSalesVisualController@index')->name('sales.visual.new');
Route::post('sales/visual/new/sale', 'Sales\NewSalesVisualController@getSales')->name('sales.visual.new.sale');
Route::post('sales/visual/new/getSubcategory', 'Sales\NewSalesVisualController@getSubcategory')->name('sales.visual.new.getSubcategory');
Route::post('sales/visual/new/subcategory_shipped_cogs', 'Sales\NewSalesVisualController@getSubcategoryShippedCOGS')->name('sales.visual.new.subcategory.shipped_cogs');
Route::post('sales/visual/new/subcategory_net_receipts', 'Sales\NewSalesVisualController@getSubcategoryNetReceipts')->name('sales.visual.new.subcategory.net_receipts');
Route::post('sales/visual/new/subcategory_po_confirmed_rate', 'Sales\NewSalesVisualController@getSubcategoryPoConfirmedRate')->name('sales.visual.new.subcategory.po_confirmed_rate');
Route::post('sales/visual/new/subcategory_sip', 'Sales\NewSalesVisualController@getSIPSubcategoryValue')->name('sales.visual.new.subcategory.sip');

Route::get('sales/load', 'Sales\LoadSalesController@index')->name('sales.load');
Route::post('sales/load/daily', 'Sales\LoadSalesController@loadDailySales')->name('sales.load.daily');
Route::post('sales/load/weekly', 'Sales\LoadSalesController@loadWeeklySales')->name('sales.load.weekly');
Route::post('sales/load/monthly', 'Sales\LoadSalesController@loadMonthlySales')->name('sales.load.monthly');

Route::get('inventory', 'Inventory\DailyInventoryController@index')->name('inventory');
Route::post('inventory/store', 'Inventory\DailyInventoryController@store')->name('inventory.store');
Route::post('inventory/store/vendor', 'Inventory\DailyInventoryController@storevendor')->name('inventory.store.vendor');
Route::get('inventory/verify_all', 'Inventory\DailyInventoryController@verifyAll')->name('inventory.verify_all');
Route::get('inventory/verify/{vendor}', 'Inventory\DailyInventoryController@verifyByVendor')->name('inventory.verify');
Route::put('inventory/destroyDate/{vendor}', 'Inventory\DailyInventoryController@destroyByDate')->name('inventory.destroyDate');
Route::put('inventory/destroy/{vendor}', 'Inventory\DailyInventoryController@destroy')->name('inventory.destroy');
Route::get('inventory/moveAllToCore', 'Inventory\DailyInventoryController@moveAllToCore')->name('inventory.moveAllToCore');
Route::get('inventory/moveToCore/{vendor}', 'Inventory\DailyInventoryController@moveToCore')->name('inventory.moveToCore');
Route::get('inventory/load', 'Inventory\LoadInventoryController@index')->name('inventory.load');
Route::post('inventory/load/daily', 'Inventory\LoadInventoryController@loadDailyInventory')->name('inventory.load.daily');
Route::post('inventory/load/weekly', 'Inventory\LoadInventoryController@loadWeeklyInventory')->name('inventory.load.weekly');
Route::post('inventory/load/monthly', 'Inventory\LoadInventoryController@loadMonthlyInventory')->name('inventory.load.monthly');

Route::get('inventory-monthly', 'Inventory\MonthlyInventoryController@index')->name('inventory-monthly');
Route::post('inventory-monthly/store', 'Inventory\MonthlyInventoryController@store')->name('inventory-monthly.store');
Route::get('inventory-monthly/verify_all', 'Inventory\MonthlyInventoryController@verifyAll')->name('inventory-monthly.verify_all');
Route::get('inventory-monthly/verify/{vendor}', 'Inventory\MonthlyInventoryController@verifyByVendor')->name('inventory-monthly.verify');
Route::put('inventory-monthly/destroyDate/{vendor}', 'Inventory\MonthlyInventoryController@destroyByDate')->name('inventory-monthly.destroyDate');
Route::put('inventory-monthly/destroy/{vendor}', 'Inventory\MonthlyInventoryController@destroy')->name('inventory-monthly.destroy');
Route::get('inventory-monthly/moveAllToCore', 'Inventory\MonthlyInventoryController@moveAllToCore')->name('inventory-monthly.moveAllToCore');
Route::get('inventory-monthly/moveToCore/{vendor}', 'Inventory\MonthlyInventoryController@moveToCore')->name('inventory-monthly.moveToCore');

Route::resource('/sales', 'DailySalesController');
Route::resource('/category', 'CategoryController');
Route::resource('/detailsale', 'DetailSalesController');
Route::resource('/verify', 'VerifySalesController');
Route::get('verify/vendors/{verify}', 'VerifySalesController@AssociatedVendors')->name('verify.vendors');
Route::put('verify/destroy/{vendor}/{date}', 'VerifySalesController@destroy')->name('verify.destroy');
Route::put('verify/destroyVendor/{vendor}', 'VerifySalesController@destroyVendor')->name('verify.destroyVendor');
Route::get('verify/moveToCore/{vendor}', 'VerifySalesController@moveToCore')->name('verify.moveToCore');
Route::get('moveToCore', 'VerifySalesController@saleToCore')->name('verify.saleToCore');

Route::resource('/verifyPtp', 'PtpVerifyController');
Route::post('verifyPtp/store/{vendor}', 'PtpVerifyController@store')->name('verifyPtp.store');
Route::get('ptpMoveData', 'PtpVerifyController@ptpMoveData')->name('verifyPtp.ptpMoveData');

Route::resource('/verifyCategory', 'CategoryVerifyController');
Route::post('verifyCategory/store/{vendor}', 'CategoryVerifyController@store')->name('verifyCategory.store');
Route::get('categoryMoveData', 'CategoryVerifyController@categoryMoveData')->name('verifyCategory.categoryMoveData');

Route::group(['middleware' => ['superAdmin']], function () {
    Route::resource('/role', 'RoleController');
    Route::post('role/authorization/{role}', 'RoleController@updateAuthorization')->name('roleAuthorization');
});
// middleware grouping for User based authorization permission
Route::group(['middleware' => ['authorizedUser']], function () {
    Route::get('user/authorization/{user}', 'UserPermissionController@show')->name('user.authorization');
    Route::post('user/authorization/update/{user}', 'UserPermissionController@updateUserAuthorization')->name('user.authorization.update');
});

Route::get('purchase/upload', 'PurchaseOrder\PurchaseOrderController@index')->name('purchase.upload');
Route::post('purchase/store/{vendor}', 'PurchaseOrder\PurchaseOrderController@purchaseOrderStoreRecords')->name('purchase.store');
Route::get('/purchaseVerify', 'PurchaseOrder\PurchaseOrderController@verify')->name('purchaseVerify.verify');
Route::get('purchaseVerify/moveToCore/{vendor}', 'PurchaseOrder\PurchaseOrderController@moveToCore')->name('purchaseVerify.moveToCore');
Route::get('purchaseVerify/vendors/{verify}', 'PurchaseOrder\PurchaseOrderController@AssociatedVendors')->name('purchaseVerify.vendors');
Route::put('purchaseVerify/destroy/{vendor}/{date}', 'PurchaseOrder\PurchaseOrderController@destroy')->name('purchaseVerify.destroy');
Route::put('purchaseVerify/destroyVendor/{vendor}', 'PurchaseOrder\PurchaseOrderController@destroyVendor')->name('purchaseVerify.destroyVendor');
Route::get('purchaseVerify/moveToCore/{vendor}', 'PurchaseOrder\PurchaseOrderController@moveToCore')->name('purchaseVerify.moveToCore');
Route::get('purchaseOrder/load', 'PurchaseOrder\LoadPurchaseOrderController@index')->name('purchaseOrder.load');
Route::post('purchaseOrder/load/daily', 'PurchaseOrder\LoadPurchaseOrderController@loadDailyPo')->name('purchaseOrder.load.daily');
Route::post('purchaseOrder/load/weekly', 'PurchaseOrder\LoadPurchaseOrderController@loadWeeklyPo')->name('purchaseOrder.load.weekly');
Route::post('purchaseOrder/load/monthly', 'PurchaseOrder\LoadPurchaseOrderController@loadMonthlyPo')->name('purchaseOrder.load.monthly');

Route::get('sellerCenter', 'SellerCenter\SellerCenterController@index')->name('sellerCenter.index');
Route::post('sellerCenter/store', 'SellerCenter\SellerCenterController@store')->name('sellerCenter.store');
Route::get('sellerCenter/verifyAll', 'SellerCenter\SellerCenterController@verifyAll')->name('sellerCenter.verifyAll');
Route::get('sellerCenter/verify/{vendor}', 'SellerCenter\SellerCenterController@verifyByVendor')->name('sellerCenter.verify');
Route::get('sellerCenter/moveAllToCore', 'SellerCenter\SellerCenterController@moveAllToCore')->name('sellerCenter.moveAllToCore');
Route::get('sellerCenter/moveToCore/{vendor}', 'SellerCenter\SellerCenterController@moveToCore')->name('sellerCenter.moveToCore');
Route::put('sellerCenter/destroyDate/{vendor}', 'SellerCenter\SellerCenterController@destroyByDate')->name('sellerCenter.destroyDate');
Route::put('sellerCenter/destroy/{vendor}', 'SellerCenter\SellerCenterController@destroy')->name('sellerCenter.destroy');
Route::get('sellerCenter/load', 'SellerCenter\LoadSellerCenterController@index')->name('sellerCenter.load');
Route::post('sellerCenter/load/daily', 'SellerCenter\LoadSellerCenterController@loadDailyDropship')->name('sellerCenter.load.daily');

Route::get('dropship', 'Dropship\DropshipController@index')->name('dropship.index');
Route::post('dropship/store', 'Dropship\DropshipController@store')->name('dropship.store');
Route::get('dropship/verifyAll', 'Dropship\DropshipController@verifyAll')->name('dropship.verifyAll');
Route::get('dropship/moveAllToCore', 'Dropship\DropshipController@moveAllToCore')->name('dropship.moveAllToCore');
Route::get('dropship/moveToCore/{vendor}', 'Dropship\DropshipController@moveToCore')->name('dropship.moveToCore');
Route::put('dropship/destroy/{vendor}', 'Dropship\DropshipController@destroy')->name('dropship.destroy');
Route::get('dropship/load', 'Dropship\LoadDropshipController@index')->name('dropship.load');
Route::post('dropship/load/daily', 'Dropship\LoadDropshipController@loadDailyDropship')->name('dropship.load.daily');
Route::post('dropship/load/weekly', 'Dropship\LoadDropshipController@loadWeeklyDropship')->name('dropship.load.weekly');
Route::post('dropship/load/monthly', 'Dropship\LoadDropshipController@loadMonthlyDropship')->name('dropship.load.monthly');
Route::put('dropship/removeDuplication', 'Dropship\DropshipController@removeDuplication')->name('dropship.removeDuplication');

Route::get('/ams/errors', 'AMS\AmsLogController@index')->name('ams.errors');
Route::get('/ams/verifyCampaign', 'AMS\AmsVerifyController@index')->name('ams.verifyCampaign');
Route::get('/ams/verifyCampaign/SB', 'AMS\AmsVerifyController@verifySb')->name('ams.verifyCampaign/SB');
Route::get('/ams/verifyCampaign/SD', 'AMS\AmsVerifyController@verifySd')->name('ams.verifyCampaign/SD');
Route::get('/ams/deleteDuplicationSp', 'AMS\AmsVerifyController@deleteDuplicationSp')->name('ams.deleteDuplicationSp');
Route::get('/ams/deleteDuplicationSb', 'AMS\AmsVerifyController@deleteDuplicationSb')->name('ams.deleteDuplicationSb');
Route::get('/ams/deleteDuplicationSd', 'AMS\AmsVerifyController@deleteDuplicationSd')->name('ams.deleteDuplicationSd');
Route::get('/ams/moveAllToCore', 'AMS\AmsVerifyController@moveAllToCore')->name('ams.moveAllToCore');

Route::get('/ams/DetailverifyCampaign/{vendor}', 'AMS\AmsVerifyController@DetailverifySp')->name('ams.DetailverifyCampaign');
Route::get('/ams/DetailverifyCampaignSb/{vendor}', 'AMS\AmsVerifyController@DetailverifySb')->name('ams.DetailverifyCampaignSb');
Route::get('/ams/DetailverifyCampaignSd/{vendor}', 'AMS\AmsVerifyController@DetailverifySd')->name('ams.DetailverifyCampaignSd');
Route::get('/ams/deleteCampaignSp/{vendor}', 'AMS\AmsVerifyController@destroyCampaignSp')->name('ams.deleteCampaignSp');
Route::get('/ams/deleteCampaignSb/{vendor}', 'AMS\AmsVerifyController@destroyCampaignSb')->name('ams.deleteCampaignSb');
Route::get('/ams/deleteCampaignSd/{vendor}', 'AMS\AmsVerifyController@destroyCampaignSd')->name('ams.deleteCampaignSd');
Route::put('ams/destroyCampaignSp/{vendor}', 'AMS\AmsVerifyController@destroyCampaipaignSpByDate')->name('ams.destroyCampaignSp');
Route::put('ams/destroyCampaignSb/{vendor}', 'AMS\AmsVerifyController@destroyCampaipaignSbByDate')->name('ams.destroyCampaignSb');
Route::put('ams/destroyCampaignSd/{vendor}', 'AMS\AmsVerifyController@destroyCampaipaignSdByDate')->name('ams.destroyCampaignSd');

Route::get('campaign/load', 'AMS\AmsCampaignLoadController@index')->name('campaign.load');
Route::post('campaing/load/daily', 'AMS\AmsCampaignLoadController@loadDailyCampaing')->name('campaing.load.daily');
Route::post('campaing/load/weekly', 'AMS\AmsCampaignLoadController@loadWeeklyCampaing')->name('campaing.load.weekly');
Route::post('campaing/load/monthly', 'AMS\AmsCampaignLoadController@loadMonthlyCampaing')->name('campaing.load.monthly');
Route::get('/ams/load/dashboard', 'AMS\AmsCampaignLoadController@Dashboard')->name('ams.load.dashboard');

Route::get('search_term/load', 'AMS\AmsSearchTermLoadController@index')->name('searchTerm.load');
Route::post('search_term/load/daily', 'AMS\AmsSearchTermLoadController@loadDailySearchTerm')->name('searchTerm.load.daily');
Route::post('search_term/load/weekly', 'AMS\AmsSearchTermLoadController@loadWeeklySearchTerm')->name('searchTerm.load.weekly');
Route::post('search_term/load/monthly', 'AMS\AmsSearchTermLoadController@loadMonthlySearchTerm')->name('searchTerm.load.monthly');
Route::get('/ams/load/dashboard/search_term', 'AMS\AmsSearchTermLoadController@dashboard')->name('ams.load.dashboard.search_term');

Route::get('product_ads/load', 'AMS\AmsProductAdsLoadController@index')->name('ProductAds.load');
Route::post('product_ads/load/daily', 'AMS\AmsProductAdsLoadController@loadDailyProductAds')->name('ProductAds.load.daily');
Route::post('product_ads/load/weekly', 'AMS\AmsProductAdsLoadController@loadWeeklyProductAds')->name('ProductAds.load.weekly');
Route::post('product_ads/load/monthly', 'AMS\AmsProductAdsLoadController@loadMonthlyProductAds')->name('ProductAds.load.monthly');
Route::get('/ams/load/dashboard/product_ads', 'AMS\AmsProductAdsLoadController@dashboard')->name('ams.load.dashboard.product_ads');

Route::get('target/load', 'AMS\AmsTargetingLoadController@index')->name('target.load');
Route::post('target/load/daily', 'AMS\AmsTargetingLoadController@loadDailyTargeting')->name('target.load.daily');
Route::post('keyword/load/daily', 'AMS\AmsTargetingLoadController@loadDailyKeyword')->name('keyword.load.daily');

Route::get('/ams/amsVerify', 'AMS\AmsTargetKeywordVerifyController@index')->name('ams.amsVerify');
Route::post('/ams/amsVerify/verifySB', 'AMS\AmsTargetKeywordVerifyController@verifySb')->name('ams.amsVerify.verifySB');
Route::post('/ams/amsVerify/verifySD', 'AMS\AmsTargetKeywordVerifyController@verifySd')->name('ams.amsVerify.verifySD');
Route::post('ams/amsVerify/report', 'AMS\AmsTargetKeywordVerifyController@getReportsData')->name('ams.amsVerify.report');
Route::get('/ams/amsVerify/amsDashboard', 'AMS\AmsTargetKeywordVerifyController@Dashboard')->name('ams.amsVerify.amsDashboard');
Route::get('/ams/deleteDuplicationSp', 'AMS\AmsTargetKeywordVerifyController@deleteDuplicationSp')->name('ams.deleteDuplicationSp');
Route::get('/ams/deleteDuplicationSb', 'AMS\AmsTargetKeywordVerifyController@deleteDuplicationSb')->name('ams.deleteDuplicationSb');
Route::get('/ams/deleteDuplicationSd', 'AMS\AmsTargetKeywordVerifyController@deleteDuplicationSd')->name('ams.deleteDuplicationSd');
Route::get('/ams/amsVerify/moveAllToCore', 'AMS\AmsTargetKeywordVerifyController@moveAllToCore')->name('ams.amsVerify.moveAllToCore');
Route::get('/ams/amsVerify/generateLogTable', 'AMS\AmsTargetKeywordVerifyController@generateLogTable')->name('ams.amsVerify.generateLogTable');


Route::get('/ams/DetailAmsVerify/{id}/{type}/{start_dt}/{end_dt}', 'AMS\AmsTargetKeywordVerifyController@DetailverifySp')->name('ams.DetailAmsVerify');
Route::get('/ams/DetailAmsVerifySb/{id}/{type}/{start_dt}/{end_dt}', 'AMS\AmsTargetKeywordVerifyController@DetailverifySb')->name('ams.DetailAmsVerifySb');
Route::get('/ams/DetailAmsVerifySd/{id}/{type}/{start_dt}/{end_dt}', 'AMS\AmsTargetKeywordVerifyController@DetailverifySd')->name('ams.DetailAmsVerifySd');
Route::get('/ams/deleteAmsSp/{vendor}', 'AMS\AmsTargetKeywordVerifyController@destroyAmsSp')->name('ams.deleteAmsSp');
Route::get('/ams/deleteAmsSb/{vendor}', 'AMS\AmsTargetKeywordVerifyController@destroyAmsSb')->name('ams.deleteAmsSb');
Route::get('/ams/deleteAmsSd/{vendor}', 'AMS\AmsTargetKeywordVerifyController@destroyAmsSd')->name('ams.deleteAmsSd');
Route::put('ams/destroyAmsSp/{vendor}/{type}', 'AMS\AmsTargetKeywordVerifyController@destroyAmsSpByDate')->name('ams.destroyAmsSp');
Route::put('ams/destroyAmsSb/{vendor}/{type}', 'AMS\AmsTargetKeywordVerifyController@destroyAmsSbByDate')->name('ams.destroyAmsSb');
Route::put('ams/destroyAmsSd/{vendor}/{type}', 'AMS\AmsTargetKeywordVerifyController@destroyAmsSdByDate')->name('ams.destroyAmsSd');

Route::get('salesOrdered', 'SalesOrderedRevenue\SalesOrderedController@index')->name('salesOrderedRevenue.index');
Route::post('salesOrder/store', 'SalesOrderedRevenue\SalesOrderedController@store')->name('salesOrder.store');
Route::get('salesOrder/verify_all', 'SalesOrderedRevenue\SalesOrderedController@verifyAll')->name('salesOrder.verify_all');
Route::get('salesOrder/verify/{vendor}', 'SalesOrderedRevenue\SalesOrderedController@verifyByVendor')->name('salesOrder.verify');
Route::put('salesOrder/destroyDate/{vendor}', 'SalesOrderedRevenue\SalesOrderedController@destroyByDate')->name('salesOrder.destroyDate');
Route::put('salesOrder/destroy/{vendor}', 'SalesOrderedRevenue\SalesOrderedController@destroy')->name('salesOrder.destroy');
Route::get('salesOrder/moveAllToCore', 'SalesOrderedRevenue\SalesOrderedController@moveAllToCore')->name('salesOrder.moveAllToCore');
Route::get('salesOrder/moveToCore/{vendor}', 'SalesOrderedRevenue\SalesOrderedController@moveToCore')->name('salesOrder.moveToCore');
Route::get('salesOrder/load', 'SalesOrderedRevenue\LoadSalesOrderController@index')->name('salesOrder.load');
Route::post('salesOrder/load/daily', 'SalesOrderedRevenue\LoadSalesOrderController@loadDailySalesOrder')->name('salesOrder.load.daily');
Route::post('salesOrder/load/weekly', 'SalesOrderedRevenue\LoadSalesOrderController@loadWeeklySalesOrder')->name('salesOrder.load.weekly');
Route::post('salesOrder/load/monthly', 'SalesOrderedRevenue\LoadSalesOrderController@loadMonthlySalesOrder')->name('salesOrder.load.monthly');

Route::get('traffic', 'Traffic\TrafficController@index')->name('traffic.index');
Route::post('traffic/store', 'Traffic\TrafficController@store')->name('traffic.store');
Route::get('traffic/verify_all', 'Traffic\TrafficController@verifyAll')->name('traffic.verify_all');
Route::get('traffic/verify/{vendor}', 'Traffic\TrafficController@verifyByVendor')->name('traffic.verify');
Route::put('traffic/destroyDate/{vendor}', 'Traffic\TrafficController@destroyByDate')->name('traffic.destroyDate');
Route::put('traffic/destroy/{vendor}', 'Traffic\TrafficController@destroy')->name('traffic.destroy');
Route::get('traffic/moveAllToCore', 'Traffic\TrafficController@moveAllToCore')->name('traffic.moveAllToCore');
Route::get('traffic/moveToCore/{vendor}', 'Traffic\TrafficController@moveToCore')->name('traffic.moveToCore');
Route::get('traffic/load', 'Traffic\LoadTrafficController@index')->name('traffic.load');
Route::post('traffic/load/daily', 'Traffic\LoadTrafficController@loadDailyTraffic')->name('traffic.load.daily');
Route::post('traffic/load/weekly', 'Traffic\LoadTrafficController@loadWeeklyTraffic')->name('traffic.load.weekly');
Route::post('traffic/load/monthly', 'Traffic\LoadTrafficController@loadMonthlyTraffic')->name('traffic.load.monthly');


Route::get('process', 'Process\ProcessController@index')->name('process');

Route::get('businessView/visual', 'BusinessView\BusinessViewController@index')->name('businessView.visual');
Route::post('businessView/visual/kpi', 'BusinessView\BusinessViewController@getBusinessViewKPI')->name('businessView.visual.kpi');
Route::post('businessView/visual/totalAdSales', 'BusinessView\BusinessViewController@getTotalAdSalesByType')->name('businessView.visual.totalAdSales');
Route::post('businessView/visual/campaignSpend', 'BusinessView\BusinessViewController@getCampaignSpendByType')->name('businessView.visual.campaignSpend');
Route::get('businessView/visual/getSearchTermSPData', 'BusinessView\BusinessViewController@getSearchTermSPData')->name('businessView.visual.getSearchTermSPData');
Route::get('businessView/visual/exportSearchTermSPData', 'BusinessView\BusinessViewController@exportSearchTermSPData')->name('businessView.visual.exportSearchTermSPData');
Route::post('businessView/visual/getTopASINData', 'BusinessView\BusinessViewController@getTopASINData')->name('businessView.visual.getTopASINData');
Route::get('businessView/visual/getOrderedSalesSpendData', 'BusinessView\BusinessViewController@getOrderedSalesSpendData')->name('businessView.visual.getOrderedSalesSpendData');
Route::post('businessView/visual/getPortfolioData', 'BusinessView\BusinessViewController@getPortfolioData')->name('businessView.visual.getPortfolioData');
Route::get('businessView/visual/exportPortfolioData', 'BusinessView\BusinessViewController@exportPortfolioData')->name('businessView.visual.exportPortfolioData');
Route::post('businessView/visual/lineGraphByType', 'BusinessView\BusinessViewController@getLineGrapgByType')->name('businessView.visual.lineGraphByType');
Route::get('businessView/visual/exportPerformanceOverTimeData', 'BusinessView\BusinessViewController@exportPerformanceOverTimeData')->name('businessView.visual.exportPerformanceOverTimeData');
Route::post('businessView/visual/dateCheck', 'BusinessView\BusinessViewController@dateCheck')->name('businessView.visual.dateCheck');


Route::get('flywheel/visual', 'Flywheel\FlywheelController@index')->name('flywheel.visual');
Route::post('flywheel/visual/OrderedRevAdSales', 'Flywheel\FlywheelController@getOrderedRevenueSpAdSalesData')->name('flywheel.visual.OrderedRevAdSales');
Route::post('flywheel/visual/ConversionAsp', 'Flywheel\FlywheelController@getConversionsAspData')->name('flywheel.visual.ConversionAsp');
Route::post('flywheel/visual/spImpressionsGlanceView', 'Flywheel\FlywheelController@getSpImpressionsGlanceViewData')->name('flywheel.visual.spImpressionsGlanceView');
Route::post('flywheel/visual/inventoryOrderedUnit', 'Flywheel\FlywheelController@getInventoryOrderedUnitData')->name('flywheel.visual.inventoryOrderedUnit');
Route::post('flywheel/visual/spendByAdType', 'Flywheel\FlywheelController@getSpendByAdTypeData')->name('flywheel.visual.spendByAdType');
Route::post('flywheel/visual/salesByAdType', 'Flywheel\FlywheelController@getSalesByAdTypeData')->name('flywheel.visual.salesByAdType');
Route::post('flywheel/visual/categoryDetail', 'Flywheel\FlywheelController@getCategoryDetailData')->name('flywheel.visual.categoryDetail');
Route::get('flywheel/visual/dataforselect2', 'Flywheel\FlywheelController@getdataforselect2')->name('flywheel.visual.dataforselect2');
Route::get('flywheel/visual/dataforasinselect2', 'Flywheel\FlywheelController@getdataforAsinselect2')->name('flywheel.visual.dataforasinselect2');
Route::get('flywheel/visual/dataforcategoryselect2', 'Flywheel\FlywheelController@getdataforCategoryelect2')->name('flywheel.visual.dataforcategoryselect2');

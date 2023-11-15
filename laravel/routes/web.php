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
//Auth::routes(['verify' => true]);
Auth::routes();
// envio de correos desde pagina principal
Route::post('/sendpay', 'MailController@sendpay')->name('sendForm');
// envio de correos desde pagina principal
Route::post('/sendForm', 'MailController@mailsend')->name('sendForm');
//envio de documentos y sus archivos
Route::get('/sendInvoice/{key}', 'MailController@sendInvoice')->name('sendInvoice');
Route::post('/sendInvoicecc', 'MailController@sendInvoicecc')->name('sendInvoicecc');
//envio de documentos y sus archivos
Route::get('/sendExpense/{key}', 'MailController@sendExpense')->name('sendExpense');

//Home Routes

Route::resource('/home', 'HomeController')->middleware('auth');
//Companies Routes
Route::get('/changePlanCompany/{plan}/{id}', 'CompaniesController@changePlanCompany')->name('changePlanCompany')->middleware('auth');
Route::get('/changeStateCompany/{id}/{state}', 'CompaniesController@changeStateCompany')->name('changeStateCompany')->middleware('auth');
Route::get('/adminCompanies', 'CompaniesController@adminCompanies')->name('adminCompanies')->middleware('auth');
Route::post('/changeCompany', 'CompaniesController@change')->name('changeCompany')->middleware('auth');
Route::post('/updateCryptoKey', 'CompaniesController@updateCryptoKey')->name('updateCryptoKey')->middleware('auth');
Route::get('/configuration','CompaniesController@configuration')->name('configuration')->middleware('auth');
Route::resource('/companies','CompaniesController')->middleware('auth');
//Company_Economic_Activities Routes
Route::resource('/companieseconomicactivities','CompaniesEconomicActivitiesController')->middleware('auth');
//Branch_Offices Routes
Route::resource('/branchoffices','BranchOfficesController')->middleware('auth');
//Clients Routes
Route::resource('/clients','ClientsController')->middleware('auth');
//Consecutives Routes
Route::resource('/consecutives','ConsecutivesController')->middleware('auth');
//Providers Routes
Route::get('/plusConsecutiveFR/{key}', 'PaymentController@plusConsecutiveFR')->name('plusConsecutiveFR');
Route::post('/purchaseOptions', 'PaymentController@purchaseOptions')->name('purchaseOptions');
Route::post('/invoiceData', 'PaymentController@companyData')->name('invoiceData');
Route::post('/receivepayment', 'PaymentController@receivepayment')->name('receivepayment');
Route::resource('/payments','PaymentController');
//Providers Routes
Route::resource('/providers','ProvidersController')->middleware('auth');
//Discounts Routes
Route::resource('/discounts','DiscountsController')->middleware('auth');
//Documents Routes
Route::get('/getVoucherView/{key}', 'VouchersController@getView')->name('getVoucherView')->middleware('auth');
Route::post('/proccess/{action}/{key}', 'VouchersController@proccess')->name('proccess')->middleware('auth');
Route::post('/importVoucher', 'VouchersController@import')->name('importVoucher')->middleware('auth');
Route::resource('/vouchers','VouchersController')->middleware('auth');
//Taxes Routes
Route::resource('/taxes','TaxesController')->middleware('auth');
//Taxes_code Routes
Route::resource('/taxe_codes','TaxesCodeController')->middleware('auth');
//Taxes_code Routes
Route::get('/rate_codes/getall','RateCodeController@getall')->name('getall')->middleware('auth');
Route::resource('/rate_codes','RateCodeController')->middleware('auth');
//Taxes Routes
Route::resource('/exonerations','ExonerationsController')->middleware('auth');
//Products Routes
Route::resource('/products','ProductsController')->middleware('auth');
//importacion de documentos
Route::get('/printTicket/{key}', 'DocumentsController@printTicket')->name('printTicket')->middleware('auth');
Route::get('/importDocView', 'DocumentsController@importDocView')->name('importDocView')->middleware('auth');
Route::post('/importChargeFile', 'DocumentsController@importChargeFile')->name('importChargeFile')->middleware('auth');
Route::post('/importDocSave', 'DocumentsController@importDocSave')->name('importDocSave')->middleware('auth');
//Documents Routes
Route::post('/addDocument', 'DocumentsController@create')->name('addDocument')->middleware('auth');
Route::get('/saveQB/{key}/{status}', 'DocumentsController@saveQB')->name('saveQB')->middleware('auth');
Route::get('/consultState/{key}', 'DocumentsController@consultState')->name('consultState')->middleware('auth');
Route::resource('/documents','DocumentsController')->middleware('auth');
//Expenses Routes
Route::post('/addExpense', 'ExpensesController@create')->name('addExpense')->middleware('auth');
Route::get('/saveEQB/{key}', 'ExpensesController@saveEQB')->name('saveEQB')->middleware('auth');
Route::get('/changeCategory/{value}/{id}', 'ExpensesController@changeCategory')->name('changeCategory')->middleware('auth');
Route::get('/getExpenseView/{key}', 'ExpensesController@getView')->name('getExpenseView')->middleware('auth');
Route::get('/consultStateE/{key}', 'ExpensesController@consultState')->name('consultStateE')->middleware('auth');
Route::resource('/expenses','ExpensesController')->middleware('auth');

//Skus Routes
Route::resource('/skus','SkusController')->middleware('auth');

Route::get('/viewInvoice','PDFController@viewInvoice')->name('viewInvoice')->middleware('auth');

Route::post('/saveImage', 'CompaniesController@saveImage')->name('saveImage')->middleware('auth');

//routes address
Route::get('cantonByProvince/{id}', 'CantonsController@byProvince')->name('cantonByProvince')->middleware('auth');
Route::get('districtByCanton/{id}', 'DistrictsController@byCanton')->name('districtByCanton')->middleware('auth');

Auth::routes();

//Users Routes
Route::get('/changeRollUser/{roll}/{id}', 'UsersController@changeRollUser')->name('changeRollUser')->middleware('auth');
Route::get('/changeStateUser/{id}/{state}', 'UsersController@changeStateUser')->name('changeStateUser')->middleware('auth');
Route::post('/addAccountant','UsersController@addAccountant')->name('addAccountant')->middleware('auth');
Route::resource('/users','UsersController')->middleware('auth');

Route::get('/home', 'HomeController@index')->name('home')->middleware('auth');

//enlaces de exportacion
Route::get('/IVAFileExport', 'ExportController@exportFileIVA')->name('IVAFileExport')->middleware('auth');

//reportes
Route::post('/profitAndLost','ReportController@profitAndLost')->name('profitAndLost')->middleware('auth');
Route::get('/profitAndLost','ReportController@profitAndLost')->name('profitAndLost')->middleware('auth');

Route::post('/dSales','ReportController@dSales')->name('dSales')->middleware('auth');
Route::get('/dSales','ReportController@dSales')->name('dSales')->middleware('auth');

Route::post('/dExpenses','ReportController@dExpenses')->name('dExpenses')->middleware('auth');
Route::get('/dExpenses','ReportController@dExpenses')->name('dExpenses')->middleware('auth');

Route::post('/ivaReport','ReportController@ivaReport')->name('ivaReport')->middleware('auth');
Route::get('/ivaReport','ReportController@ivaReport')->name('ivaReport')->middleware('auth');

Route::get('/billing','BillingController@index')->name('billing')->middleware('auth');


<?php

use App\Http\Livewire\Accounts\AccountList;
use App\Http\Livewire\Accounts\AccountTypeList;
use App\Http\Controllers\MainController;
use App\Http\Livewire\Categories\CategoryList;
use App\Http\Livewire\Companies\CompanyList;
use App\Http\Livewire\CompanySelect;
use App\Http\Livewire\Currencies\CurrencyList;
use App\Http\Livewire\Materials\MaterialCategoryList;
use App\Http\Livewire\Materials\MaterialList;
use App\Http\Livewire\Materials\UnitList;
use App\Http\Livewire\Sales\CreateInvoice;
use App\Http\Livewire\Sales\CustomerList;
use App\Http\Livewire\Roles\RoleList;
use App\Http\Livewire\Purchases\ExpenseList;
use App\Http\Livewire\Sales\InvoiceList;
use App\Http\Livewire\Sales\RevenueList;
use App\Http\Livewire\Purchases\SupplierList;
use App\Http\Livewire\Taxes\TaxList;
use App\Http\Livewire\Taxes\WithHoldingList;
use App\Http\Livewire\Users\UserList;
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
Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {

    Route::get('/company/select', CompanySelect::class)->name('company.select');
    Route::get('/companies', CompanyList::class)->middleware('PermissionCheck:companies,read')->name('companies');

    Route::group(['middleware' => ['SelectCompany']], function () {
        Route::get('locale/{locale}', [MainController::class, 'makeLocale']);

        Route::get('/', function () {
            return view('welcome');
        })->name('dashboard');

        Route::get('/dashboard', function () {
            return view('welcome');
        })->name('dashboard');

        Route::group(['prefix' => 'settings', 'middleware' => ['PermissionCheck:settings,read']], function (){
            Route::get('/translations', [Barryvdh\TranslationManager\Controller::class, "getIndex"])->middleware('PermissionCheck:translations,read')->name('translations');
            Route::get('/currencies', CurrencyList::class)->middleware('PermissionCheck:currencies,read')->name('currencies');
            Route::get('/categories', CategoryList::class)->middleware('PermissionCheck:categories,read')->name('categories');
            Route::get('/taxes', TaxList::class)->middleware('PermissionCheck:taxes,read')->name('taxes');
            Route::get('/with-holdings', WithHoldingList::class)->middleware('PermissionCheck:with-holdings,read')->name('with_holdings');
        });

        Route::group(['prefix' => 'material-management', 'middleware' => ['PermissionCheck:material-management,read']], function (){
            Route::get('/units', UnitList::class)->middleware('PermissionCheck:units,read')->name('units');
            Route::get('/material-categories', MaterialCategoryList::class)->middleware('PermissionCheck:material-category,read')->name('material_category');
            Route::get('/materials', MaterialList::class)->middleware('PermissionCheck:materials,read')->name('materials');
        });

        Route::group(['prefix' => 'user-management', 'middleware' => ['PermissionCheck:user-management,read']], function (){
            Route::get('/users', UserList::class)->middleware('PermissionCheck:users,read')->name('users');
            Route::get('/roles', RoleList::class)->middleware('PermissionCheck:roles,read')->name('roles');
        });

        Route::group(['prefix' => 'sales', 'middleware' => ['PermissionCheck:sales,read']], function (){
            Route::get('/customers', CustomerList::class)->middleware('PermissionCheck:customers,read')->name('customers');
            Route::get('/revenues', RevenueList::class)->middleware('PermissionCheck:revenues,read')->name('revenues');
            Route::get('/invoices', InvoiceList::class)->middleware('PermissionCheck:invoices,read')->name('invoices');
            Route::get('/invoice/{id}', CreateInvoice::class)->middleware('PermissionCheck:invoices,create')->name('create.invoice');
        });

        Route::group(['prefix' => 'purchases', 'middleware' => ['PermissionCheck:purchases,read']], function (){
            Route::get('/suppliers', SupplierList::class)->middleware('PermissionCheck:suppliers,read')->name('suppliers');
            Route::get('/expenses', ExpenseList::class)->middleware('PermissionCheck:expenses,read')->name('expenses');
        });

        Route::group(['prefix' => 'banks', 'middleware' => ['PermissionCheck:banks,read']], function (){
            Route::get('/accounts', AccountList::class)->middleware('PermissionCheck:accounts,read')->name('accounts');
            Route::get('/types', AccountTypeList::class)->middleware('PermissionCheck:account_types,read')->name('accounts.types');
        });
    });

});

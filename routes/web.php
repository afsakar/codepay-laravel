<?php

use App\Http\Livewire\Accounts\AccountList;
use App\Http\Livewire\Accounts\AccountTypeList;
use App\Http\Controllers\MainController;
use App\Http\Livewire\Companies\CompanyList;
use App\Http\Livewire\Roles\RoleList;
use App\Http\Livewire\Suppliers\SupplierList;
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

    Route::get('locale/{locale}', [MainController::class, 'makeLocale']);

    Route::get('/', function () {
        return view('welcome');
    })->name('dashboard');

    Route::get('/dashboard', function () {
        return view('welcome');
    })->name('dashboard');

    Route::group(['prefix' => 'user-management', 'middleware' => ['PermissionCheck:user-management,read']], function (){
        Route::get('/roles', RoleList::class)->middleware('PermissionCheck:roles,read')->name('roles');
    });

    Route::get('/companies', CompanyList::class)->middleware('PermissionCheck:companies,read')->name('companies');

    Route::get('/suppliers', SupplierList::class)->middleware('PermissionCheck:suppliers,read')->name('suppliers');

    Route::get('/translations', ["Barryvdh\TranslationManager\Controller::class", "getIndex"])->middleware('PermissionCheck:translations,read')->name('translations');

    Route::group(['prefix' => 'accounts'], function (){
        Route::get('/', AccountList::class)->middleware('PermissionCheck:accounts,read')->name('accounts');
        Route::get('/types', AccountTypeList::class)->middleware('PermissionCheck:account_types,read')->name('accounts.types');
    });

});

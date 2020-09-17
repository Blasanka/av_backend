<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::post('supplier_login', 'Auth\SupplierAuthController@login');
Route::post('supplier_registration', 'Dashboard\SupplierManagement@storeSupplierDetails');
Route::get('get_profile_details', 'Dashboard\SupplierManagement@getSingleSupplierDetails');
Route::post('update_supplier_details', 'Dashboard\SupplierManagement@updateSupplierDetails');
Route::post('addNewProduct', 'Dashboard\ProductManagement@addNewProduct');


// Admin: temporary
Route::post('admin/login', 'Auth\AdminAuthController@login');
Route::get('admin/products', 'Dashboard\admin\ProductController@getAllProducts');
Route::put('admin/products', 'Dashboard\admin\ProductController@changeStatus');

// Products to Suplier Dashboard
Route::post('supplier/products', 'Dashboard\supplier\SuplierProductController@addNewProduct');
Route::post('supplier/products/image', 'Dashboard\supplier\SuplierProductController@addNewProductImage');
Route::get('supplier/products', 'Dashboard\supplier\SuplierProductController@getAllProducts');

// Products to AV
Route::get('products/latest', 'av\AVProductController@getFeaturedProducts');
Route::get('products', 'av\AVProductController@getAllProducts');
Route::get('products/{id}', 'av\AVProductController@getProduct');

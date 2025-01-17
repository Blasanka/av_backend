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

Route::get('admin/suppliers', 'Dashboard\admin\ManageSupplierController@getAllSuppliers');
Route::put('admin/suppliers', 'Dashboard\admin\ManageSupplierController@changeStatus');
Route::get('admin/suppliers/{id}', 'Dashboard\admin\ManageSupplierController@getSupplier');

// Products to Suplier Dashboard
Route::post('supplier/products', 'Dashboard\supplier\SuplierProductController@addNewProduct');
Route::post('supplier/products/image', 'Dashboard\supplier\SuplierProductController@addNewProductImage');
Route::get('supplier/products', 'Dashboard\supplier\SuplierProductController@getAllProducts');
Route::get('supplier/products/{id}', 'Dashboard\supplier\SuplierProductController@getProduct');
Route::delete('supplier/products/{id}', 'Dashboard\supplier\SuplierProductController@deleteProduct');
Route::put('supplier/products/{id}', 'Dashboard\supplier\SuplierProductController@updateProduct');

// Categories to Dashboard
Route::post('categories', 'Dashboard\CategoryController@addCategory');
Route::put('categories/{id}', 'Dashboard\CategoryController@updateCategory');
Route::delete('categories/{id}', 'Dashboard\CategoryController@deleteCategory');

Route::post('sub-categories', 'Dashboard\CategoryController@addSubCategory');
Route::put('sub-categories/{id}', 'Dashboard\CategoryController@updateSubCategory');
Route::delete('sub-categories/{id}', 'Dashboard\CategoryController@deleteSubCategory');

// Products to AV
Route::get('products/latest', 'av\AVProductController@getFeaturedProducts');
Route::get('products', 'av\AVProductController@getAllProducts');
Route::get('products/{id}', 'av\AVProductController@getProduct');
Route::get('products/related/{subCategoryId}', 'av\AVProductController@getRelatedProducts');
Route::get('products/may-like/{categoryId}', 'av\AVProductController@getYouMayLikeProducts');

// Categories to AV
Route::get('categories', 'av\AVCategoryController@getAllCategories');
Route::get('categories/{id}', 'av\AVCategoryController@getCategory');

Route::get('sub-categories', 'av\AVCategoryController@getAllSubCategories');
Route::get('sub-categories/{id}', 'av\AVCategoryController@getSubCategory');

// Search to AV
Route::post('search/products', 'av\AVProductController@searchProducts');

// Auth for AV
Route::post('login', 'Auth\AVAuthController@login');
Route::post('register', 'Auth\AVAuthController@register');
Route::post('request-code', 'av\AvCustomerController@generateVerificationCode');
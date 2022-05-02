<?php

use App\Http\Controllers\Api\Backend\CategoryController;
use App\Http\Controllers\Api\Backend\ReportController;
use App\Http\Controllers\Api\Backend\SubCategoryController;
use App\Http\Controllers\Api\Backend\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\Backend\UserController as Admin;
use App\Http\Controllers\Api\SchoolController;
use App\Http\Controllers\Api\StudentController;
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
Route::group(['namespace' => 'App\Http\Controllers\apis'], function () {
/************************************************************************************* */
/**********************************************admin routes*************************** */
Route::group(['namespace' =>'Backend' , 'prefix' => '/backend'], function () {
    Route::post('login',[Admin::class,'login']);
Route::group(['middleware' => ['auth:admin']], function () {
    //categories
    Route::get('categories',[CategoryController::class,'index']);
    Route::post('categories/add',[CategoryController::class,'add']);
    Route::get('categories/show',[CategoryController::class,'show']);
    Route::post('categories/update',[CategoryController::class,'update']);
    Route::get('categories/delete',[CategoryController::class,'delete']);
    //sub Categories
    Route::get('sub_categories',[SubCategoryController::class,'index']);
    Route::post('sub_categories/add',[SubCategoryController::class,'add']);
    Route::get('sub_categories/show',[SubCategoryController::class,'show']);
    Route::post('sub_categories/update',[SubCategoryController::class,'update']);
    Route::get('sub_categories/delete',[SubCategoryController::class,'delete']);
    //Transactions
    Route::get('transactions',[TransactionController::class,'index']);
    Route::post('transactions/add',[TransactionController::class,'add']);
    Route::get('transactions/show',[TransactionController::class,'show']);
    Route::post('transactions/add-payment',[TransactionController::class,'addPayment']);
    //reports
    Route::get('reports',[ReportController::class,'index']);
    //users
    Route::get('users',[Admin::class,'users']);
    
});
});
//************************************************************************************ */
/************************************************************************************* */
/**********************************************Users Route *************************** */

Route::post('login',[UserController::class,'login']);
Route::group(['middleware' => 'auth:user'], function(){
    Route::get('transactions',[UserController::class,'transactions']);
});
//************************************************************************************ */
/************************************************************************************* */
});
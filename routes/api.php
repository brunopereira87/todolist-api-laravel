<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TaskController;

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
//   return $request->user();
// });

// $prefix = '/auth';
Route::get('/ping',function(){
  return ['pong' => true];
});
Route::get('/401',[AuthController::class,'unauthorized'])->name('login');
Route::prefix('auth')->group( function(){
  Route::post("/login",[AuthController::class,'login']);
  // Route::post("/forgot",[AuthController::class,'forgot']);
  // Route::post("/reset",[AuthController::class,'reset']);
  Route::post("/register",[AuthController::class,'create']);
  Route::get("/logged",[AuthController::class,'logged']);
  Route::post("/logout",[AuthController::class,'logout']);
});


$prefix = '/users';

Route::get("$prefix",[UserController::class,'read']);
Route::put("$prefix",[UserController::class,'update']);

Route::prefix('/tasks')->group(function(){
  Route::get("/",[TaskController::class,'read']);
  Route::post("/",[TaskController::class,'create']);
  Route::get("/{id}",[TaskController::class,'read']);
  Route::put("/{id}",[TaskController::class,'update']);
  Route::put("/{id}/done",[TaskController::class,'done']);
  Route::delete("/{id}",[TaskController::class,'delete']);
});

Route::prefix('categories')->group(function(){
  Route::get("/",[CategoryController::class,'read']);
  Route::post("/",[CategoryController::class,'create']);
  Route::get("/{id}",[CategoryController::class,'read']);
  Route::get("/{category_id}/tasks",[TaskController::class,'readCategoryTasks']);
  Route::put("/{id}",[CategoryController::class,'update']);
  Route::delete("/{id}",[CategoryController::class,'delete']);
});


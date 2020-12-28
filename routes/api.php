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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// $prefix = '/auth';

// $router->post("$prefix/login",'AuthController@login');
// $router->post("$prefix/forgot",'AuthController@forgot');
// $router->post("$prefix/reset",'AuthController@reset');
// $router->post("$prefix/register",'AuthController@create');
// $router->get("$prefix/logged",'AuthController@logged');
// $router->post("$prefix/logout",'AuthController@logout');

// $prefix = '/users';

// $router->get("$prefix",'UserController@read');
// $router->get("$prefix/{id}",'UserController@read');
// $router->put("$prefix/{id}",'UserController@update');

// $prefix = '/tasks';

// $router->get("$prefix",'TaskController@read');
// $router->post("$prefix",'TaskController@create');
// $router->get("$prefix/{id}",'TaskController@read');
// $router->put("$prefix/{id}",'TaskController@update');
// $router->put("$prefix/{id}/done",'TaskController@done');
// $router->delete("$prefix/{id}",'TaskController@delete');

// $prefix = '/categories';

// $router->get("$prefix",'CategoryController@read');
// $router->post("$prefix",'CategoryController@create');
// $router->get("$prefix/{id}",'CategoryController@read');
// $router->put("$prefix/{id}",'CategoryController@update');
// $router->delete("$prefix/{id}",'CategoryController@delete');

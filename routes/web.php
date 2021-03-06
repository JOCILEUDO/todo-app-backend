<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

// API route group
$router->group(['prefix' => 'api'], function () use ($router) {
    $router->post('register', 'AuthController@register');
    $router->post('login', 'AuthController@login'); 
    $router->get('me', 'UserController@profile');
    
    $router->group(['prefix' => 'auth'], function() use ($router) {
        $router->put('update', 'UserController@update');
    });
    
    $router->group(['prefix' => 'user'], function() use ($router) {
        $router->get('/', 'UserController@index');
    });

    $router->group(['prefix' => 'category'], function() use ($router) {
        $router->get('/',   'CategoryController@index');
        $router->post('/',      'CategoryController@store');
        $router->get('/{id}',   'CategoryController@show');
        $router->put('/{id}',   'CategoryController@update');
        $router->delete('/{id}',   'CategoryController@destroy');
    });

    $router->group(['prefix' => 'tag'], function() use ($router) {
        $router->get('/', 'TagController@index');
        $router->post('/', 'TagController@store');
        $router->get('/{id}', 'TagController@show');
        $router->put('/{id}', 'TagController@update');
        $router->delete('/{id}', 'TagController@destroy');
    });

    $router->group(['prefix' => 'activity'], function() use ($router) {
        $router->get('/', 'ActivityController@index');
        $router->post('/', 'ActivityController@store');
        $router->get('/{id}', 'ActivityController@show');
        $router->put('/{id}', 'ActivityController@update');
        $router->delete('/{id}', 'ActivityController@destroy');
    });
});

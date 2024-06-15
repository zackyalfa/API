<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->get('/stuffs', 'StuffController@index');
$router->post('/stuffs/store', 'StuffController@store');
$router->get('/stuffs/trash', 'StuffController@trash');

$router->get('/user', 'UserController@index');
$router->post('/user/store', 'UserController@store');
$router->get('/user/trash', 'UserController@trash');

$router->post('/inbound-stuffs/store', 'inboundStuffController@store');

$router->post('/lending/store','LendingController@store');
$router->get('/lendings','LendingController@index');

//dinamis
$router->get('/stuffs/{id}', 'StuffController@show');
$router->patch('/stuffs/update/{id}', 'StuffController@update');
$router->delete('/stuffs/delete/{id}', 'StuffController@destroy');
$router->get('/stuffs/trash/restore/{id}', 'StuffController@restore');
$router->get('/stuffs/trash/delete-permanent/{id}', 'Stuffcontroller@permanentdelete');

$router->get('/user/{id}', 'UserController@show');
$router->patch('/user/{id}', 'UserController@update');
$router->delete('/user/{id}', 'UserController@destroy');
$router->get('/user/trash/restore/{id}', 'UserController@restore');
$router->get('/user/trash/delete-permanent/{id}', 'UserController@permanentdelete');

$router->delete('/inbound-stuffs/delete/{id}','InboundStuffController@destroy');
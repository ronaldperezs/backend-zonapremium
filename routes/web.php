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

use App\Http\Controllers\UsersController;

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/authorizate', [
    'uses' => 'OAuth2Controller@authorizate'
]);

$router->get('/users', [
    'middleware' => 'auth',
    'uses' => 'UsersController@index'
]);

$router->get('/users/{id}', [
    'middleware' => 'auth',
    'uses' => 'UsersController@show'
]);

$router->post('/users/misaldo', [
    'middleware' => 'auth',
    'uses' => 'UsersController@misaldo'
]);

$router->post('/users', [
    'uses' => 'UsersController@create'
]);

$router->put('/users/{id}', [
    'middleware' => 'auth',
    'uses' => 'UsersController@update'
]);

$router->delete('/users/{id}', [
    'middleware' => 'auth',
    'uses' => 'UsersController@delete'
]);

$router->get('/plataformas', [
    'middleware' => 'auth',
    'uses' => 'PlataformaController@index'
]);

$router->get('/plataformas/{id}', [
    'middleware' => 'auth',
    'uses' => 'PlataformaController@show'
]);

$router->get('/plataformas/{id}/planes', [
    'middleware' => 'auth',
    'uses' => 'PlataformaController@planes'
]);

$router->post('/plataformas', [
    'middleware' => 'auth',
    'uses' => 'PlataformaController@create'
]);

$router->put('/plataformas/{id}', [
    'middleware' => 'auth',
    'uses' => 'PlataformaController@update'
]);

$router->delete('/plataformas/{id}', [
    'middleware' => 'auth',
    'uses' => 'PlataformaController@delete'
]);

$router->get('/planes', [
    'middleware' => 'auth',
    'uses' => 'PlanController@index'
]);

$router->get('/planes/{id}', [
    'middleware' => 'auth',
    'uses' => 'PlanController@show'
]);

$router->post('/planes', [
    'middleware' => 'auth',
    'uses' => 'PlanController@create'
]);

$router->put('/planes/{id}', [
    'middleware' => 'auth',
    'uses' => 'PlanController@update'
]);

$router->delete('/planes/{id}', [
    'middleware' => 'auth',
    'uses' => 'PlanController@delete'
]);

$router->get('/suscriptions', [
    'middleware' => 'auth',
    'uses' => 'SuscriptionsController@index'
]);

$router->get('/suscriptions/{id}', [
    'middleware' => 'auth',
    'uses' => 'SuscriptionsController@show'
]);

$router->post('/suscriptions', [
    'middleware' => 'auth',
    'uses' => 'SuscriptionsController@create'
]);

$router->put('/suscriptions/{id}', [
    'middleware' => 'auth',
    'uses' => 'SuscriptionsController@update'
]);

$router->delete('/suscriptions/{id}', [
    'middleware' => 'auth',
    'uses' => 'SuscriptionsController@delete'
]);

$router->get('/compras', [
    'middleware' => 'auth',
    'uses' => 'ComprasController@index'
]);

$router->get('/compras/{id}', [
    'middleware' => 'auth',
    'uses' => 'ComprasController@show'
]);

$router->post('/compras', [
    'middleware' => 'auth',
    'uses' => 'ComprasController@create'
]);

$router->post('/compras/saldo/{id}', [
    'middleware' => 'auth',
    'uses' => 'ComprasController@comprarConSaldo'
]);

$router->get('/response', [
    'uses' => 'ComprasController@response'
]);

$router->post('/confirmation', [
    'middleware' => 'auth',
    'uses' => 'ComprasController@confirmation'
]);

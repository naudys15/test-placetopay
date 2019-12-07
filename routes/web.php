<?php

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
$requestNamespace = 'Request\\';
$responseNamespace = 'Response\\';

Route::get('/', ['as' => 'index', 'uses' => $requestNamespace.'RequestToPay@index']);
Route::post('/sendRequest', ['as' => 'send', 'uses' => $requestNamespace.'RequestToPay@sendRequest']);
Route::get('/responseRequest', ['as' => 'receive', 'uses' => $responseNamespace.'ResponseToPay@receiveRequest']);

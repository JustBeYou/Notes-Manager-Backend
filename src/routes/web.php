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

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->get('notes', ['uses' => 'NoteController@showAllNotes']);
    $router->get('notes/{id}', ['uses' => 'NoteController@showOneNote']);
    $router->post('notes', ['uses' => 'NoteController@create']);
    $router->delete('notes/{id}', ['uses' => 'NoteController@delete']);
    $router->put('notes/{id}', ['uses' => 'NoteController@update']);
    $router->post('notes/{id}/upload', ['uses' => 'NoteController@upload']);
    $router->get('notes/{id}/download', ['uses' => 'NoteController@download']);
});

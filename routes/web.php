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

use App\Models\Currency;

$router->get('/', function () use ($router) {
    return $router->app->version();
});


$router->get(
    '/currencies',
    function () use ($router) {
        $all = Currency::all();
        return $all;
    }
);

$router->get(
    '/currencies/{id}',
    function ($id) use ($router) {
        $curr = Currency::findOrFail( $id );
        return $curr;
    }
);

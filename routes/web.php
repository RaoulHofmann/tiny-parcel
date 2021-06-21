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

// Routing group using Auth0 middleware
$router->group(['prefix' => 'api', 'middleware' => 'auth'], function () use ($router) {

  // Pricing Models routes
  $router->get('pricing_models',  ['uses' => 'PricingModelController@getPricingModels']);
  $router->get('pricing_models/parcels',  ['uses' => 'PricingModelController@getPricingModelParcels']);

  // Parcel Information routes
  $router->get('parcels',  ['uses' => 'ParcelInformationController@getAllParcelInformation']);
  $router->get('parcels/{id}',  ['uses' => 'ParcelInformationController@getParcelInformation']);
  $router->post('parcels',  ['uses' => 'ParcelInformationController@addParcelInformation']);
  $router->put('parcels',  ['uses' => 'ParcelInformationController@updateParcelInformation']);
  $router->delete('parcels/{id}',  ['uses' => 'ParcelInformationController@deleteParcelInformation']);

  $router->get('pricess/{parcelIds?}',  ['uses' => 'ParcelInformationController@getParcelPricing']);
});

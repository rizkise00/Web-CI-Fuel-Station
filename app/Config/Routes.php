<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Login
$routes->get('/', 'AuthController::index');
$routes->post('/login', 'AuthController::login');
$routes->get('/logout', 'AuthController::logout');

// Home
$routes->get('/home', 'HomeController::index');
$routes->get('/home/add-fuel-station', 'HomeController::add');
$routes->post('/home/store-fuel-station', 'HomeController::store');
$routes->get('/home/edit-fuel-station/(:num)', 'HomeController::edit/$1');
$routes->post('/home/update-fuel-station/(:num)', 'HomeController::update/$1');
$routes->get('/home/delete-fuel-station/(:num)', 'HomeController::delete/$1');

// Users
$routes->get('users', 'UserController::index');
$routes->get('users/add', 'UserController::add');
$routes->post('users/store', 'UserController::store');
$routes->get('/users/edit/(:num)', 'UserController::edit/$1');
$routes->post('/users/update/(:num)', 'UserController::update/$1');
$routes->get('/users/delete/(:num)', 'UserController::delete/$1');

$routes->group('api', function($routes) {
    $routes->get('fuel-station/list', 'HomeController::fuelStationList');
});
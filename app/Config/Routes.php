<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// app/Config/Routes.php

$routes->group('api/v1', function($routes) {
    // Rute untuk mengambil semua item
    $routes->get('items', 'ApiController::getItems');
    // Rute untuk menambahkan item baru
    $routes->post('items', 'ApiController::addItem');
    // Rute untuk mengupdate item
    $routes->put('items/(:num)', 'ApiController::updateItem/$1');
    // Rute untuk menghapus item
    $routes->delete('items/(:num)', 'ApiController::deleteItem/$1');
    // Rute untuk login dengan POST method
    $routes->post('login', 'ApiController::login');
    $routes->post('users/create', 'Api\UserController::create');
// $routes->get('users/profile', 'Api\UserController::getProfile'); // Untuk mendapatkan profil
    $routes->get('users/profile', 'ApiController::profile'); // Untuk mendapatkan profil
//  $routes->put('users/update', 'Api\UserController::updateProfile'); // Untuk memperbarui profil
    $routes->get('users/profile/(:num)', 'UserController::profile/$1');
    $routes->get('notifications/(:num)', 'NotificationController::show/$1');
    $routes->get('notifications', 'NotificationController::index');
    $routes->get('dictionary', 'Api\DictionaryApiController::show');
    $routes->post('dictionary/create', 'Api\DictionaryApiController::create');

});
$routes->get('create-account', 'Home::createAccount');


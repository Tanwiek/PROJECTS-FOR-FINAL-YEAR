<?php
// public/index.php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/Core/Router.php';

// Simple Autoloader
spl_autoload_register(function ($class) {
    $path = __DIR__ . '/../src/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($path)) {
        require_once $path;
    }
});

$router = new Core\Router();

// Define Routes
$router->add('GET', '/', 'AuthController@showLogin');
$router->add('POST', '/login', 'AuthController@login');
$router->add('GET', '/logout', 'AuthController@logout');
$router->add('GET', '/dashboard', 'DashboardController@index');

// Tenders
$router->add('GET', '/tenders', 'TenderController@index');
$router->add('GET', '/tenders/create', 'TenderController@create');
$router->add('POST', '/tenders/store', 'TenderController@store');

// Projects
$router->add('GET', '/projects', 'ProjectController@index');
$router->add('GET', '/projects/create', 'ProjectController@create');
$router->add('GET', '/projects/show', 'ProjectController@show');
$router->add('POST', '/projects/store', 'ProjectController@store');

// Invoices
$router->add('GET', '/invoices', 'InvoiceController@index');
$router->add('GET', '/invoices/create', 'InvoiceController@create');
$router->add('POST', '/invoices/store', 'InvoiceController@store');

// Suppliers
$router->add('GET', '/suppliers', 'SupplierController@index');
$router->add('GET', '/suppliers/create', 'SupplierController@create');

// Payments
$router->add('GET', '/payments', 'PaymentController@index');
$router->add('GET', '/payments/record', 'PaymentController@record');

// Offers
$router->add('GET', '/offers', 'OfferController@index');
$router->add('GET', '/offers/create', 'OfferController@create');

// Orders
$router->add('GET', '/orders', 'OrderController@index');
$router->add('GET', '/orders/create', 'OrderController@create');

// Deliveries
$router->add('GET', '/deliveries', 'DeliveryController@index');
$router->add('GET', '/deliveries/create', 'DeliveryController@create');

// Reports
$router->add('GET', '/reports', 'ReportController@index');

// Archives
$router->add('GET', '/archives', 'ArchiveController@index');
$router->add('GET', '/archives/archive', 'ArchiveController@archive');
$router->add('GET', '/archives/unarchive', 'ArchiveController@unarchive');
$router->add('GET', '/archives/download', 'ArchiveController@download');

// Programmer (Super Admin)
$router->add('GET', '/programmer', 'ProgrammerController@index');
$router->add('GET', '/programmer/users', 'ProgrammerController@users');
$router->add('POST', '/programmer/users/store', 'ProgrammerController@storeUser');
$router->add('GET', '/programmer/users/delete', 'ProgrammerController@deleteUser');
$router->add('GET', '/programmer/logs', 'ProgrammerController@logs');

// Documents
$router->add('POST', '/documents/upload', 'DocumentController@upload');

// Dispatch
$router->dispatch();

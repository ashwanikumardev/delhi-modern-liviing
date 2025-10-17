<?php
session_start();
require_once 'config/config.php';
require_once 'config/database.php';
require_once 'helpers/url_helper.php';
require_once 'core/Router.php';
require_once 'core/Controller.php';
require_once 'core/Model.php';

// Auto-load controllers and models
spl_autoload_register(function ($class) {
    $paths = [
        'controllers/' . $class . '.php',
        'models/' . $class . '.php',
        'core/' . $class . '.php'
    ];
    
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

$router = new Router();

// User routes
$router->add('/', 'HomeController@index');
$router->add('/home', 'HomeController@index');
$router->add('/about', 'HomeController@about');
$router->add('/contact', 'HomeController@contact');
$router->add('/rooms', 'RoomController@index');
$router->add('/rooms/{id}', 'RoomController@show');
$router->add('/cart', 'CartController@index');
$router->add('/checkout', 'CheckoutController@index');
$router->add('/orders', 'OrderController@index');
$router->add('/orders/{id}/download', 'OrderController@download');
$router->add('/profile', 'ProfileController@index');

// Auth routes
$router->add('/auth/signup', 'AuthController@signup');
$router->add('/auth/login', 'AuthController@login');
$router->add('/auth/logout', 'AuthController@logout');
$router->add('/auth/forgot-password', 'AuthController@forgotPassword');
$router->add('/auth/reset-password', 'AuthController@resetPassword');

// Admin routes
$router->add('/admin', 'AdminController@dashboard');
$router->add('/admin/dashboard', 'AdminController@dashboard');
$router->add('/admin/login', 'AdminAuthController@login');
$router->add('/admin/logout', 'AdminAuthController@logout');

// Admin Users
$router->add('/admin/users', 'AdminUserController@index');
$router->add('/admin/users/create', 'AdminUserController@create');
$router->add('/admin/users/{id}', 'AdminUserController@show');
$router->add('/admin/users/{id}/edit', 'AdminUserController@edit');
$router->add('/admin/users/{id}/delete', 'AdminUserController@delete');

// Admin Rooms
$router->add('/admin/rooms', 'AdminRoomController@index');
$router->add('/admin/rooms/create', 'AdminRoomController@create');
$router->add('/admin/rooms/{id}/edit', 'AdminRoomController@edit');
$router->add('/admin/rooms/{id}/delete', 'AdminRoomController@delete');

// Admin Bookings
$router->add('/admin/bookings', 'AdminBookingController@index');
$router->add('/admin/bookings/create', 'AdminBookingController@create');
$router->add('/admin/bookings/{id}/view', 'AdminBookingController@viewBooking');
$router->add('/admin/bookings/{id}/approve', 'AdminBookingController@approve');
$router->add('/admin/bookings/{id}/reject', 'AdminBookingController@reject');

// Admin Orders
$router->add('/admin/orders', 'AdminOrderController@index');
$router->add('/admin/orders/create', 'AdminOrderController@create');
$router->add('/admin/orders/{id}', 'AdminOrderController@show');
$router->add('/admin/orders/{id}/update-status', 'AdminOrderController@updateStatus');
$router->add('/admin/orders/{id}/delete', 'AdminOrderController@delete');
$router->add('/admin/orders/export', 'AdminOrderController@export');

// Admin Tickets
$router->add('/admin/tickets', 'AdminTicketController@index');
$router->add('/admin/tickets/{id}/view', 'AdminTicketController@viewTicket');
$router->add('/admin/tickets/{id}/reply', 'AdminTicketController@reply');

// Admin Reports
$router->add('/admin/reports', 'AdminReportController@index');
$router->add('/admin/reports/revenue', 'AdminReportController@revenue');
$router->add('/admin/reports/users', 'AdminReportController@users');

// Admin Settings
$router->add('/admin/settings', 'AdminSettingController@index');
$router->add('/admin/settings/update', 'AdminSettingController@update');

// Order routes
$router->add('/orders', 'OrderController@index');
$router->add('/orders/{id}', 'OrderController@show');
$router->add('/orders/{id}/invoice', 'OrderController@invoice');

// Export routes
$router->add('/admin/users/export', 'AdminUserController@export');
$router->add('/admin/bookings/export', 'AdminBookingController@export');
$router->add('/admin/reports/export', 'AdminReportController@export');

// API routes
$router->add('/api/cart/add', 'CartController@add');
$router->add('/api/cart/remove', 'CartController@remove');
$router->add('/api/payment/process', 'PaymentController@process');
$router->add('/api/check-email', 'ApiController@checkEmail');

try {
    // Debug: Log the URI being processed
    error_log("Processing URI: " . $_SERVER['REQUEST_URI']);
    $router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
} catch (Exception $e) {
    // Debug: Log the error
    error_log("Router error: " . $e->getMessage());
    http_response_code(404);
    include 'views/errors/404.php';
}

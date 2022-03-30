<?php
require_once __DIR__. '/../vendor/autoload.php';

use Supri\LoginManagementVersigue\app\App\Database;
use Supri\LoginManagementVersigue\app\Controller\HomeController;
use Supri\LoginManagementVersigue\app\App\Router;
use Supri\LoginManagementVersigue\app\Controller\UserController;
use Supri\LoginManagementVersigue\app\Middleware\MustLoginMiddleware;
use Supri\LoginManagementVersigue\app\Middleware\MustNotLoginMiddleware;

Database::getDatabseConnection('prod');

// Home Controller
Router::add('GET', '/',HomeController::class, 'index', []);

// user Controller

Router::add('GET', '/users/register', UserController::class, 'register', [MustNotLoginMiddleware::class]);
Router::add('POST', '/users/register', UserController::class, 'postRegister', [MustNotLoginMiddleware::class]);
Router::add('GET', '/users/login', UserController::class, 'login', [MustNotLoginMiddleware::class]);
Router::add('POST', '/users/login', UserController::class, 'postLogin', [MustNotLoginMiddleware::class]);
Router::add('GET', '/users/logout', UserController::class, 'logout', [MustLoginMiddleware::class]);
Router::add('GET', '/users/profile', UserController::class, 'updateProfile', [MustLoginMiddleware::class]);
Router::add('POST', '/users/profile', UserController::class, 'postUpdateProfile', [MustLoginMiddleware::class]);
Router::add('GET', '/users/password', UserController::class, 'updatePassword', []);
Router::add('POST', '/users/password', UserController::class, 'postUpdatePassword', []);

Router::run();
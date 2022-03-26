<?php
require_once __DIR__. '/../vendor/autoload.php';

use Supri\LoginManagementVersigue\app\Controller\HomeController;
use Supri\LoginManagementVersigue\app\App\Router;
use Supri\LoginManagementVersigue\app\Controller\UserController;

// Home Controller
Router::add('GET', '/',HomeController::class, 'index', []);

// user Controller

Router::add('GET', '/users/register', UserController::class, 'register', []);
Router::add('POST', '/users/register', UserController::class, 'postRegister', []);
Router::add('GET', '/users/login', UserController::class, 'login', []);
Router::add('POST', '/users/login', UserController::class, 'postLogin', []);
Router::add('GET', '/users/logout', UserController::class, 'logout', []);
Router::add('GET', '/users/profile', UserController::class, 'updateProfile', []);
Router::add('POST', '/users/profile', UserController::class, 'postUpdateProfile', []);
Router::add('GET', '/users/password', UserController::class, 'updatePassword', []);
Router::add('POST', '/users/password', UserController::class, 'postUpdatePassword', []);

Router::run();
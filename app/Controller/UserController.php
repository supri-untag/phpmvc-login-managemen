<?php

namespace Supri\LoginManagementVersigue\app\Controller;

use Supri\LoginManagementVersigue\app\App\View;

class UserController
{
    public function register()
    {
       View::render('user/register', $model);
    }
    public function login()
    {
       View::render('user/login', $model);
    }
}

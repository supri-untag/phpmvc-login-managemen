<?php

namespace Supri\LoginManagementVersigue\app\Controller;
use Supri\LoginManagementVersigue\app\App\View;

class HomeController
{
    public function index(): void
    {
        $model = [
            "tittle" => "Belajar PHP Login management"
        ];

        View::render('home/index', $model);
    }
}

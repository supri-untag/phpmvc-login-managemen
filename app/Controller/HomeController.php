<?php

namespace Supri\LoginManagementVersigue\app\Controller;

use Supri\LoginManagementVersigue\app\App\Database;
use Supri\LoginManagementVersigue\app\App\View;
use Supri\LoginManagementVersigue\app\Repository\SessionRepository;
use Supri\LoginManagementVersigue\app\Repository\UserRepository;
use Supri\LoginManagementVersigue\app\Service\SessionService;

class HomeController
{
    private SessionService $SessionService;

    public function __construct()
    {
        $connection = Database::getDatabseConnection();
        $userRepository = new UserRepository($connection);
        $SessionRepository = new SessionRepository($connection);
        $this->SessionService = new SessionService($SessionRepository, $userRepository);
    }


    public function index(): void
    {
        $user = $this->SessionService->curent();
        if ($user == null){
            $model = [
                "tittle" => "Belajar PHP Login management"
            ];
            View::render('home/index', $model);
        } else {
            $model = [
                "tittle" => "Belajar PHP Login management",
                "username" => $user->name
            ];
            View::render('user/dashboard', $model);
        }
        
    }
}

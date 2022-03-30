<?php

namespace Supri\LoginManagementVersigue\app\Middleware;

use Supri\LoginManagementVersigue\app\App\Database;
use Supri\LoginManagementVersigue\app\App\View;
use Supri\LoginManagementVersigue\app\Repository\SessionRepository;
use Supri\LoginManagementVersigue\app\Repository\UserRepository;
use Supri\LoginManagementVersigue\app\Service\SessionService;

class MustNotLoginMiddleware implements Middleware
{
    private SessionService $sessionService;
	function __construct() {
        $userRepository = new UserRepository(Database::getDatabseConnection());
        $sessionRepository = new   SessionRepository(Database::getDatabseConnection());
        $this->sessionService = new SessionService($sessionRepository, $userRepository);
	}
    
	function before(): void {
        $user = $this->sessionService->curent();
        if ($user != null ){
            View::redirect('/');
        }
	}

}

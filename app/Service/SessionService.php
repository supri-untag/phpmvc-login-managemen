<?php

namespace Supri\LoginManagementVersigue\app\Service;

use Supri\LoginManagementVersigue\app\Domain\Session;
use Supri\LoginManagementVersigue\app\Domain\User;
use Supri\LoginManagementVersigue\app\Repository\SessionRepository;
use Supri\LoginManagementVersigue\app\Repository\UserRepository;

class SessionService
{
    public static String $COOKIE_NAME = "__XS"; 
    private SessionRepository $sessionRepository;
    private UserRepository $userRepository;
    public function __construct(SessionRepository $sessionRepository, UserRepository $userRepository)
    {
        $this->sessionRepository = $sessionRepository;
        $this->userRepository = $userRepository;
    }

    
    public function create(string $userId) :Session
    {
        $session = new Session();
        $session->id = uniqid();
        $session->UserId = $userId;

        $this->sessionRepository->save($session);

        setcookie(self::$COOKIE_NAME, $session->id, time() + (60 * 60 * 60 * 24 * 30), "/" );

        return $session;
    }

    public function destroy()
    {
        $sessionId = $_COOKIE[self::$COOKIE_NAME] ?? '';
        $this->sessionRepository->deleteById($sessionId);

        setcookie(self::$COOKIE_NAME,'', 1, '');
    }

    public function curent() :?User
    {
        $sessionId = $_COOKIE[self::$COOKIE_NAME] ?? '';
        $session = $this->sessionRepository->findById($sessionId);

        if ($session == null) {
            return null;
        }
        return $this->userRepository->findById($session->UserId);

    }

}
 
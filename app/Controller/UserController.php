<?php

namespace Supri\LoginManagementVersigue\app\Controller;

use Exception;
use Supri\LoginManagementVersigue\app\App\Database;
use Supri\LoginManagementVersigue\app\App\View;
use Supri\LoginManagementVersigue\app\Model\UserResgisterRequest;
use Supri\LoginManagementVersigue\app\Repository\UserRepository;
use Supri\LoginManagementVersigue\app\Service\UserServices;
use Supri\LoginManagementVersigue\app\Exception\ValidationException;
use Supri\LoginManagementVersigue\app\Model\UpdatePasswordRequest;
use Supri\LoginManagementVersigue\app\Model\UpdateProfileRequest;
use Supri\LoginManagementVersigue\app\Model\UserLoginRequest;
use Supri\LoginManagementVersigue\app\Repository\SessionRepository;
use Supri\LoginManagementVersigue\app\Service\SessionService   ;

class UserController
{
   private UserServices $userService;
   private SessionService $sessionService;

    public function __construct()
    {
       $connection = Database::getDatabseConnection();
       $userRepository = new UserRepository($connection);
       $this->userService = new UserServices($userRepository);

       $SessionRepository = new SessionRepository($connection);
       $this->sessionService = new SessionService($SessionRepository, $userRepository);
    }

    public function register()
    {
      $model = [
         "tittle" => "Register - Belajar PHP Login management"
     ];
       View::render('user/register', $model);
    }
    public function postRegister()
    {
       $request = new UserResgisterRequest();
       $request->id = $_POST['id'];
       $request->name = $_POST['name'];
       $request->password = $_POST['password'];

       try {
          $this->userService->register($request);
          View::redirect("/users/login");

       } catch (ValidationException $exception) {
         $model = [
            "tittle" => "Register - Belajar PHP Login management",
            'erorr' => $exception->getMessage()
         ];

         View::render('user/register', $model);
       }
    }

    public function login()
    {
      $model = [
         
      ];
       View::render('user/login', $model);
    }
    public function postLogin()
    {
       $request = new UserLoginRequest();
       $request->id = $_POST['id'];
       $request->password = $_POST['password'];

       try {
          $response = $this->userService->login($request);
          $this->sessionService->create($response->user->id);
          
          View::redirect('/');

       } catch (Exception $exception) {
         $model = [
            "tittle" => "Login - Belajar PHP Login management",
            'erorr' => $exception->getMessage()
         ];

         View::render('user/login', $model);
       }
    }
   public function logout()
   {
      $this->sessionService->destroy();
      View::redirect('/');
   }

   public function updateProfile()
   {
     $user = $this->sessionService->curent();
     $model = [
      "tittle" => "Update- Belajar PHP Login management",
      "user" => [
         "id" => $user->id,
         "name" => $user->name
               ]
      ];
     View::render('user/profile', $model);
   }
   public function postUpdateProfile()
   {
      $user = $this->sessionService->curent();

      $request = new UpdateProfileRequest();
      $request->id = $user->id;
      $request->name = $_POST['name'];

      try {
         $this->userService->updateProfile($request);
         View::redirect('/');
      }catch(Exception $exception) {
         $model = [
            "tittle" => "Update- Belajar PHP Login management",
            "erorr" => $exception->getMessage(),
            "user" => [
               "id" => $user->id,
               "name" =>$_POST['name']
                     ]
         ];
         View::render('user/profile', $model);
      }

   }
   public function updatePassword()
   {
      $user = $this->sessionService->curent();
      $model = [
       "tittle" => "Update- Belajar PHP Login management",
       "user" => [
          "id" => $user->id
                ]
       ];
      View::render('user/password', $model);
   }

   public function postUpdatePassword()
   {
      $user = $this->sessionService->curent();
      $request = new UpdatePasswordRequest();
      $request->id = $user->id;
      $request->oldPassword = $_POST['oldPassword'];
      $request->newPassword = $_POST['newPassword'];

      try {
         $this->userService->updatePassword($request);
         View::redirect('/');
      } catch (Exception $exception) {
         $model = [
            "tittle" => "Update- Belajar PHP Login management",
            "erorr" => $exception->getMessage(),
            "user" => [
               "id" => $user->id,
                     ]
         ];
         View::render('user/password', $model);
      }
   }
}

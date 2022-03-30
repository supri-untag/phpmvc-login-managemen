<?php

namespace Supri\LoginManagementVersigue\app\Service;

use Exception;
use Supri\LoginManagementVersigue\app\Exception\ValidationException;
use Supri\LoginManagementVersigue\app\Model\UserRegisterRespon;
use Supri\LoginManagementVersigue\app\Model\UserResgisterRequest;
use Supri\LoginManagementVersigue\app\Repository\UserRepository;
use Supri\LoginManagementVersigue\app\App\Database;
use Supri\LoginManagementVersigue\app\Domain\User;
use Supri\LoginManagementVersigue\app\Model\UpdatePasswordRequest;
use Supri\LoginManagementVersigue\app\Model\UpdatePasswordRespon;
use Supri\LoginManagementVersigue\app\Model\UpdateProfileRequest;
use Supri\LoginManagementVersigue\app\Model\UserLoginRequest;
use Supri\LoginManagementVersigue\app\Model\UserLoginRespon;
use Supri\LoginManagementVersigue\app\Model\UpdateProfileRespon;

class UserServices
{
    private UserRepository $userRepository;

    public function __construct( UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }


    public function register(UserResgisterRequest $request) : UserRegisterRespon
    {
        $this->validateUserRegistrationRequest($request);

        $user = $this->userRepository->findById($request->id);
        if ($user != null){
            throw new ValidationException(" User id all ready");
        }
        try{
            Database::beginTransaction();

            $user = new User;
            $user->id = $request->id;
            $user->name = $request->name;
            $user->password = password_hash($request->password, PASSWORD_BCRYPT);
            
            $this->userRepository->save($user);
            
            $respon = new UserRegisterRespon();
            $respon->user = $user;

            Database::commitTransaction();
            return $respon;
        }
        catch (Exception $exception){
            Database::rollbackTransaction();
            throw $exception;
        }

    }
    private function validateUserRegistrationRequest(UserResgisterRequest $request)
    {
        if ($request->id == null || $request->name == null || $request->password == null ||
            trim($request->id) == "" || trim($request->name) == "" || trim($request->password) == "") {
            throw new ValidationException("Id, Name, Password can not blank");
        }
    }
    private function validateUserLoginRequest(UserLoginRequest $request)
    {
        if ($request->id == null || $request->password == null ||
            trim($request->id) == "" || trim($request->password) == "") {
            throw new ValidationException("Id and Password can not blank");
        }
    }

    public function login(UserLoginRequest $request) :UserLoginRespon
    {
        $this->validateUserLoginRequest($request);
        $user = $this->userRepository->findById($request->id);
        if ($user == null) {
            throw new ValidationException("ID or Password is Wrong");
        }

        if (password_verify($request->password, $user->password)) {
            $response = new UserLoginRespon();
            $response->user = $user;
            return $response;
        }else {
            throw new ValidationException("ID or Password is Wrong"); 
        }
    }
    public function updateProfile(UpdateProfileRequest $request) :UpdateProfileRespon
    {
        $this->validateUpdateProfileRequest($request);

        try {
            Database::beginTransaction();
            $user = $this->userRepository->findById($request->id);
            if ($user == null){
                throw new ValidationException("User not found");
            }

            $user->name = $request->name;
            $this->userRepository->update($user);

            Database::commitTransaction();
            $response = new UpdateProfileRespon();
            $response->user = $user;
            return $response;

        }catch (Exception $exception){
            Database::rollbackTransaction();
            throw $exception;
        }
    }
    private function validateUpdateProfileRequest(UpdateProfileRequest $request) 
    {
        if ($request->id == null || $request->name == null ||
        trim($request->id) == "" || trim($request->name) == "") {
        throw new ValidationException("Id, Name can not blank");
        }
    }

    public function updatePassword(UpdatePasswordRequest $request) : UpdatePasswordRespon
    {
        $this->validateUpdatePasswordRequest($request);

        try {
            Database::beginTransaction();

            $user = $this->userRepository->findById($request->id);

            if ($user == null){
                throw new ValidationException("User Id Not Found");
            }

            if (!password_verify($request->oldPassword, $user->password)) {
                throw new ValidationException("Old password and New password not same");
            }
            $user->password = password_hash($request->newPassword, PASSWORD_BCRYPT);
            $this->userRepository->update($user);
            Database::commitTransaction();

            $response = new UpdatePasswordRespon();
            $response->user = $user;
            return $response;

        } catch (Exception $exception) {
            Database::rollbackTransaction();
            throw $exception;
        }
    }
    private function validateUpdatePasswordRequest(UpdatePasswordRequest $request)
    {
        if ($request->id == null || $request->oldPassword == null || $request->newPassword == null ||
            trim($request->id) == "" || trim($request->oldPassword) == "" || trim($request->newPassword) == "") {
            throw new ValidationException("Id, Old Password, New Password can not blank");
            }
    }
}

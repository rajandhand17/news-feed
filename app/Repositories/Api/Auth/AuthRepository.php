<?php

namespace App\Repositories\Api\Auth;

use App\Helpers\UtilityHelper;
use App\Models\User;
use App\Services\UserService;

class AuthRepository implements AuthInterface
{   
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    public function register($request)
    {
        try {
            return $this->userService->register($request);
        } catch (\Exception $e) {
            UtilityHelper::logError($e);

            return false;
        }
    }

    public function login($request)
    {
        try {
            return $this->userService->login($request);
        } catch (\Exception $e) {
            UtilityHelper::logError($e);

            return false;
        }
    }

    public function resetPassword($request)
    {
        try {
            return $this->userService->resetPassword($request);
        } catch (\Exception $e) {
            UtilityHelper::logError($e);

            return false;
        }
    }

    public function forgetPassword($request)
    {
        try {
            return $this->userService->forgetPassword($request);
        } catch (\Exception $e) {
            UtilityHelper::logError($e);

            return false;
        }
    }

    public function verifyResetCode($request)
    {
        try {
            return $this->userService->verifyResetCode($request);
        } catch (\Exception $e) {
            UtilityHelper::logError($e);

            return false;
        }
    }

    public function logout($request){
        try {
            return $this->userService->logout($request);
        } catch (\Exception $e) {
            UtilityHelper::logError($e);

            return false;
        }
    }
}
<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\UtilityHelper;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\Auth\ForgetPasswordRequest;
use App\Http\Requests\Auth\LoginFormRequest;
use App\Http\Requests\Auth\RegisterFormRequest;
use \App\Http\Requests\Auth\ResetPasswordFormRequest;
use App\Http\Resources\Auth\LoginResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use App\Repositories\Api\Auth\AuthRepository;

class AuthController extends AppBaseController
{
    private AuthRepository $authRepository;

    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function registerUser(RegisterFormRequest $request)
    {
        try {
            $register = $this->authRepository->register($request);
            if ($register['success'] == false) {
                return $this->sendError($register['message'], 401);
            }
            if ($register['success'] == true) {
                return $this->sendResponse(result: null, message: __('responses.registration_success'), code: 200);
            }

            return $this->sendError(__('responses.send_error'), 500);
        } catch (\Exception $e) {
            UtilityHelper::logError($e);
            return $this->sendError(__('responses.send_error'), 500);
        }
    }

    public function login(LoginFormRequest $request)
    {
        try {
            $login = $this->authRepository->login($request);
            if ($login['success'] == true) {
                    $response = ['token' => LoginResource::make($login), 'user' => UserResource::make($login['user'])];
                    return $this->sendResponse($response, $login['message'], 200);
            }
            if ($login['success'] == false) {
                return $this->sendError($login['message'], 401);
            }

            return $this->sendError(__('responses.send_error'), 500);
        } catch (\Exception $e) {
            UtilityHelper::logError($e);

            return $this->sendError(__('responses.send_error'), 500);
        }
    }
    public function forgetPassword(ForgetPasswordRequest $request)
    {
        try {
            $forgetpassword = $this->authRepository->forgetPassword($request);
            if ($forgetpassword['success'] == false) {
                return $this->sendError($forgetpassword['message'], 401);
            }
            if ($forgetpassword['success'] == true) {
                return $this->sendResponse([], __('responses.send_otp_success').$forgetpassword['code'], 200);
            }
        } catch (\Exception $e) {
            UtilityHelper::logError($e);

            return $this->sendError(__('responses.send_error'), 500);
        }
    }
    public function resetPassword(ResetPasswordFormRequest $request)
    {
        try {
            $verify = $this->authRepository->verifyResetCode($request);
            if ($verify['success'] === false) {
                return $this->sendError($verify['message'], 208);
            }
            $resetcode = $this->authRepository->resetPassword($request);
            if ($resetcode['success'] === true) {
                return $this->sendResponse(null, __('responses.success_reset_password'), 200);
            }
            if ($resetcode['success'] === false) {
                return $this->sendError($resetcode['message'], 403);
            }

            return $this->sendError(__('responses.send_error'), 500);
        } catch (\Exception $e) {
            UtilityHelper::logError($e);

            return $this->sendError(__('responses.send_error'), 500);
        }
    }
    public function logout(Request $request)
    {   
        try {
            $response=$this->authRepository->logout($request);
            if($response){
                return $this->sendResponse([], __('responses.logout_success'), 200);
            }
            return $this->sendError(__('responses.send_error'), 500);

        } catch (\Exception $e) {
            UtilityHelper::logError($e);
            return $this->sendError(__('responses.send_error'), 500);
        }
        
    }
}

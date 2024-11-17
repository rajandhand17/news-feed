<?php

namespace App\Repositories\Api\Auth;

interface AuthInterface
{
    public function register($request);
    public function login($request);
    public function resetPassword($request);
    public function forgetPassword($request);
    public function verifyResetCode($request);
}
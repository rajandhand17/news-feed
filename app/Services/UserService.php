<?php

namespace App\Services;

use App\Helpers\UtilityHelper;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;
class UserService
{   
    
    /**Register user */
    public function register($request)
    {
        try {
            DB::beginTransaction();
            $name = $request->first_name.' '.$request->last_name;
            $otp = random_int(1000, 9999);
            $string = Str::random(30);
            $referencecode = $request->username.Carbon::now()->format('Y');
            $user = new User();
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->full_name = $name;
            $user->username = $request->username;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();
            
            if ($user->id) {
                $data = ['subject' => __('responses.verify_your_email'), 'first_name' => $user->first_name, 'last_name' => $user->last_name, 'otp' => $user->otp];
                    $mail = true;//SendMailHelper::sendMail($user, 'email.verify_otp', $data);  
                    if ($mail) {
                        DB::commit();
                        /**sending otp on registeres email */
                        $userresponse = User::get()->where('email', $user->email);
                        $success = ['success' => true, 'user' => $userresponse];
                        return $success;
                    }
                    DB::rollback();

                    return ['success' => false, 'message' => __('responses.failed_email')];
                
                DB::rollback();

                return ['success' => false, 'message' => __('responses.failed_registration')];
            }
            DB::rollback();

            return ['success' => false, 'message' => __('responses.failed_registration')];
        } catch (\Exception $e) {
            UtilityHelper::logError($e);
            DB::rollback();

            return ['success' => false, 'message' => __('responses.send_error')];
        }
    }

    public function login($request)
    {
        try {
            /**checking user exists or not */
            $user = User::where('email', $request->email)
            ->orWhere('username', $request->email)
            ->first();
            if ($user) {
                /**check password same or not */
                if(Auth::attempt($request->all())){
                    $token = $user->createToken('MyApp')->plainTextToken;
                    $data = $user = User::where('email', $request->email)
                    ->orWhere('username', $request->email)
                    ->first();
                    return ['success' => true, 'user' => $data, 'token' => $token, 'message' => __('responses.user_login_success')];
                } else {
                    return ['success' => false, 'message' => __('responses.invalid_credentials')];
                }
            } else {
                $response = ['success' => false, 'message' => __('responses.user_not_found')];
                return $response;
            }
        } catch (\Exception $e) {
            UtilityHelper::logError($e);
            $response = ['success' => false, 'message' => __('responses.send_error')];

            return $response;
        }
    }

     /**Reset password */
     public function resetPassword($request)
     {
        try {
             /** Get records of particular user by using email */
            $user = User::where(['email' => $request->email])->first();
             /**checking otp same or not */
            $user->password = Hash::make($request->password);
            if ($user->save()) {
                $data = ['subject' => __('responses.email_subject_reset_password'), 'first_name' => $user['first_name'], 'last_name' => $user['last_name']];
                $mail = true;//SendMailHelper::sendMail($user, 'email.reset_password', $data);
                if ($mail) {
                    $success = ['success' => true, 'user' => $user];
 
                    return $success;
                }
                return ['success' => false, 'message' => __('responses.failed_email'), 'code' => 2];
            }
 
            return false;
        } catch (\Exception $e) {
            UtilityHelper::logError($e);
            $response = ['success' => false, 'message' => __('responses.send_error')];
            return $response;
        }
    }

    public function forgetPassword($request)
    {
        try {
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return false;
            } else {
                $string = Str::random(60);
                $otp = random_int(1000, 9999);
                $user->remember_token = $string;
                $user->otp = $otp;
                $user->save();
                $data = ['subject' => 'Forget Password', 'first_name' => $user['first_name'], 'last_name' => $user['last_name'], 'otp' => $user['otp']];
                $mail = true;//SendMailHelper::sendMail($user, 'email.forget_password_otp', $data); if need to send mail then need to create template and setup smpt for gmail and also create template for mail, I have wrote tempory name for email.forget_password_Otp,
                if ($mail) {
                    $success = ['success' => true, 'user' => $user, 'code' => $otp];

                    return $success;
                }

                return ['success' => false, 'message' => __('responses.failed_email')];
            }
        } catch (\Exception $e) {
            UtilityHelper::logError($e);

            return false;
        }
    }

    public function verifyResetCode($request)
    {
        try {
            /**get records of particular user by using email */
            $user = User::where(['email' => $request->email])->first();
            /**Matching otp is same or not */
            if ($user->otp == $request->otp) {
                $response = ['success' => true, 'message' => __('responses.reset_otp_verified')];

                return $response;
            } else {
                $response = ['success' => false, 'message' => __('responses.reset_otp_not_verified')];
            }
            
            return $response;
        } catch (\Exception $e) {
            UtilityHelper::logError($e);

            return false;
        }
    }

    public function logout($request){
        try {
            return $request->user()->currentAccessToken()->delete();
        } catch (\Exception $e) {
            UtilityHelper::logError($e);
            return false;
        }
    }
}

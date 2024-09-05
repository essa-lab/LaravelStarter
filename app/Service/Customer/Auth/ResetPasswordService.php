<?php
namespace App\Service\Customer\Auth;

use App\Models\Customer\Customer;
use App\Models\Customer\PasswordReset;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class ResetPasswordService{

    public function requestResetPassword($registerOption){
        $passwordReset = new PasswordReset();
        $resetCode = random_int(100000, 999999);
        $passwordReset->code = $resetCode;
        $passwordReset->registeration_option = $registerOption;
        $passwordReset->created_at = Carbon::now();
        $passwordReset->save();

        $passwordReset->sendResetPasswordEmail();
        
        return $resetCode;
    }

    public function resetPassword(array $param){
        $passwordReset = PasswordReset::where('code',$param['code'])->first();
        if($passwordReset && $passwordReset->created_at->diffInMinutes(Carbon::now()) > 30){
            Customer::where('registeration_option',$passwordReset->registeration_option)
                        ->update(['password'=>Hash::make($param['password'])]);
            
            $passwordReset->delete();
            return true;
        }
        return false;
    }
}
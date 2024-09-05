<?php

namespace App\Service\Customer\Auth;

use App\Models\Customer\Customer;
use Exception;
use Illuminate\Support\Facades\Hash;

class CustomerService
{
    public function register(array $data){
        try{
            $customer = new Customer();
            $customer->name                 = $data['f_name'].' '.$data['l_name'];
            $customer->f_name               = $data['f_name'];
            $customer->l_name               = $data['l_name'];
            $customer->password             = Hash::make($data['password']);
            $customer->registeration_option = $data['registeration_option'];
            $customer->country               = $data['country'];
            $customer->confirm_code         = bin2hex(random_bytes(20));
            $customer->activation_code      = random_int(100000, 999999);
            $customer->login_status         =1;
            $customer->save();


            $customer->reciveWelcomeEmail();
            sleep(2);
            $customer->reciveActivationEmail();
            // $customer->notify(new WelcomeNotification($data));
            // $customer->notify((new ActivationNotification($data))->delay(2));
            
            return ['registerationOption'=>$customer->registeration_option,
                    'confirmationCode'   =>$customer->confirm_code,
                    'activationCode'     =>$customer->activation_code];

        }catch(Exception $e){
            throw new \Exception('Failed to create User due to a Unexpected error. ' . $e->getMessage(), 500);
        }
    }
    public function  confirmToken($token) {
        $customer = Customer::where('confirm_code',$token)->first();
        if(!$customer){
            throw new Exception('There is no Customer with this Token');
        }
        if($customer->verified == 1 ){
            throw new Exception('Email was Verified previously');
        }

        $customer->verified = 1;
        $customer->enabled = 1;
        $customer->login_status = 2;
        $customer->save();

        return $customer;

    }
    public function  confirmCode(array $params) {
        $customer = Customer::where('activation_code',$params['code'])->where('registeration_option',$params['registeration_option'])->first();
        if(!$customer){
            throw new Exception('There is no Customer with this Token');
        }
        if($customer->verified == 1 ){
            throw new Exception('Email was Verified previously');
        }

        $customer->verified = 1;
        $customer->enabled = 1;
        $customer->login_status = 2;
        $customer->save();

        return $customer;

    }
    public function login(array $data){
        
        try{
            $customer = Customer::where('registeration_option', $data['registeration_option'])->first();

            $token = auth('mobile')->attempt($data);

            if($token){
                //
                $customer['token'] = $token;
                $customer['token_type'] = 'bearer';
                
            }
            return ['data'=>$customer,'token'=>$token];

        }catch(Exception $e){
            throw new \Exception('Faild to login ' . $e->getMessage());
        }
    }

}

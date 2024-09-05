<?php

namespace App\Models\Customer;

use App\Jobs\SendResetPasswordEmailjob;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class PasswordReset extends Model
{
    use SoftDeletes,Notifiable;
    //
    protected $table = 'password_resets';
    public $incrementing = true;
    public $timestamps = true;
    
    public function getEmailAttribute()
    {
        return $this->attributes['registeration_option'];
    }
    public function sendResetPasswordEmail(){
        SendResetPasswordEmailjob::dispatch($this);
    }
}

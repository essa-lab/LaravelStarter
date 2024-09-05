<?php
namespace App\Models\Customer;

use App\Jobs\SendActivationEmailJob;
use App\Jobs\SendWelcomeEmailJob;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Customer extends Authenticatable implements JWTSubject{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table='customers';
    protected $fillable=[
        'name',
        'f_name',
        'l_name',
        'registeration_option',
        'password',
        'country'
    ];

    protected $hidden=[
        'password',
        'activation_code',
        'confirm_code',
        'remember_token'
    ];

     /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }


    public function getEmailAttribute()
    {
        return $this->attributes['registeration_option'];
    }
    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [
            'id'              => $this->id,
            'name'            => $this->name,
            'usb'             => $this->email,
            'registeration_option'           => $this->registeration_option,
            'created_on'   => $this->created_at->toIso8601String(),
        ];
    }

    public function reciveWelcomeEmail(){
        SendWelcomeEmailJob::dispatch($this);
    }
    public function reciveActivationEmail(){
        SendActivationEmailJob::dispatch($this);
    }

}
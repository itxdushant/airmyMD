<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
// use Laravel\Sanctum\HasApiTokens;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'phone',
        'country_code',
        'email',
        'status',
        'password',
        'profile_photo_url',
        'user_type',
        'stripe_customer',
        'push_notification',
        'email_notification',
        'device_token'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
        'average_rating'
    ];


    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function patientProfile()
    {
        return $this->hasOne(PatientProfile::class);
    }
    
    public function patientAllergie()
    {
        return $this->hasMany(PatientAllergie::class);
    }
    
    public function patientMedication()
    {
        return $this->hasMany(PatientMedication::class);
    }
    
    
    public function reviews()
    {
        return $this->hasMany(Review::class, 'doctor_id');
    }
    
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating');
    }
    
    public function patientInsurance()
    {
        return $this->hasMany(PatientInsurance::class);
    }
    
    protected function defaultProfilePhotoUrl()
    {
        $user = User::with('profile', 'patientProfile')->where('id', $this->id)->first();    
        if(empty( $user->profile_photo_path) ){
            if($this->user_type == 'user'){
                
                $first_name = ($user->patientProfile->first_name)? $user->patientProfile->first_name :'';
                $last_name = ($user->patientProfile->last_name)?$user->patientProfile->last_name: '';
            }else{
                $first_name = (isset($user->profile->first_name)) ?$user->profile->first_name: '';
                $last_name = (isset($user->profile->last_name) )?$user->profile->last_name: '';
            }
            
            return 'https://ui-avatars.com/api/?name=' . urlencode($first_name.' '.$last_name) . '&color=7F9CF5&background=EBF4FF';
            
        }else{
            return env('APP_URL').'/'.$user->profile_photo_path;
        }
    }
    
    public function getProfilePhotoUrlAttribute()
    {
        $user = User::with('profile', 'patientProfile')->where('id', $this->id)->first();    
        if(empty( $user->profile_photo_path) ){
            if($this->user_type == 'user'){
               $first_name = ($user->patientProfile->first_name)? $user->patientProfile->first_name :'';
                $last_name = ($user->patientProfile->last_name)?$user->patientProfile->last_name: '';
            }else{
                 $first_name = (isset($user->profile->first_name)) ?$user->profile->first_name: '';
                $last_name = (isset($user->profile->last_name) )?$user->profile->last_name: '';
                
            }
            
            return 'https://ui-avatars.com/api/?name=' . urlencode($first_name.' '.$last_name) . '&color=7F9CF5&background=EBF4FF';
            
        }else{
            return env('APP_URL').'/'.$user->profile_photo_path;
        }
    }
    
    public function bank()
    {
        return $this->hasOne(BankInfo::class, 'user_id', 'id');
    }

    
}

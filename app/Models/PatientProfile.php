<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientProfile extends Model
{
    
    use HasFactory;
    
    public $timestamps = false;
    
    protected $appends = [
        'profile_photo_url'
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'location',
        'location',
        'latitude',
        'first_name',
        'middle_name',
        'last_name',
        'city',
        'state',
        'zip',
        'height',
        'weight',
        'gender',
        'ethnicity',
        'dob',
        'marital_status',
        'medical_history',
        'surgeries',
        'year',
        'age',
        'sexual_health',
        'habits',
        'general',
        'skin',
        'eyes',
        'cigarette',
        'tobacco',
        'street_drug',
        'needle_drug',
        'drink_alcohol',
        'how_many',
        'drinks_in_day',
        'cut_down',
        'felt_guilty',
        'morning_drink',
    ];

    public function getProfilePhotoUrlAttribute()
    {
        $user = User::with('profile', 'patientProfile')->where('id', $this->user_id)->first();    
        if(empty( $user->profile_photo_path) ){
            if($this->user_type == 'doctor'){
                $first_name = (isset($user->patientProfile->first_name)) ?$user->profile->first_name: '';
                $last_name = (isset($user->profile->last_name) )?$user->profile->last_name: '';
            }else{
                $first_name = ($user->patientProfile && $user->patientProfile->first_name)? $user->patientProfile->first_name :'';
                $last_name = ($user->patientProfile && $user->patientProfile->last_name)?$user->patientProfile->last_name: '';
            }
            
            return 'https://ui-avatars.com/api/?name=' . urlencode($first_name.' '.$last_name) . '&color=7F9CF5&background=EBF4FF';
            
        }else{
            return env('APP_URL').'/'.$user->profile_photo_path;
        }
    }
}

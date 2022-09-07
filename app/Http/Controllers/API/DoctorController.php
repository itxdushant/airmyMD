<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\PatientProfile;
use App\Models\PatientAllergie;
use App\Models\PatientMedication;
use App\Models\PatientInsurance;
use App\Models\Specialization;
use App\Models\Profile;



class DoctorController extends BaseController
{
   
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function list(Request $request)
    {
        $users = User::with(['profile' => function ($query) {
                $query->select('id','user_id','first_name','last_name', 'address', 'city', 'state', 'zipcode', 'bio', 'specialization', 'telemedicine', 'insurance', 'tags', 'latitude', 'longitude', 'fees');
            }])->where('user_type', 'doctor');
            
        if( $request->filled('latitude') ) {
            $users->join('profiles', 'profiles.user_id', '=', 'users.id');
            $lat_lng = [$request->latitude, $request->longitude];
            $haversine = "(
                3959 * acos(
                    cos(radians(" .$lat_lng[0]. "))
                    * cos(radians(`latitude`))
                    * cos(radians(`longitude`) - radians(" .$lat_lng[1]. "))
                    + sin(radians(" .$lat_lng[0]. ")) * sin(radians(`latitude`))
                )
            )";
            $users->selectRaw("users.*, profiles.user_id");
            $users->whereRaw("{$haversine} < ?", 20 );
            
        }

        if($request->filled('specialization')) {
            $specialization = $request->specialization;
             $users->whereHas('profile', function($q) use($specialization) {
                $q->where('specialization', 'like', '%'.$specialization.'%');
            });
        }
        
        $users = $users->paginate(20)->toArray();
        $data = [];
        $data['data'] = $users['data'];
        $data['total'] = $users['total'];
        //dd($data);
        return $this->sendResponse($data, 'Doctors listing.');
    }

    public function view($id)
    {
        $user = User::with('profile')->where(['user_type' => 'doctor', 'id' => $id])->first();
        if($user && $user->profile) {
            $user->profile->days = @unserialize($user->profile->days);
            $user->profile->special_days = @unserialize($user->profile->special_days);
        }
        return $this->sendResponse($user, 'Doctor Details.');
    }

    public function specializations(Request $request)
    {
        $specializations = Specialization::all()->toArray();
        if(isset($request->latitude)){
            $haversine = "(
                3959 * acos(
                    cos(radians(" .$request->latitude. "))
                    * cos(radians(`latitude`))
                    * cos(radians(`longitude`) - radians(".$request->longitude."))
                    + sin(radians(" .$request->latitude. ")) * sin(radians(`latitude`))
                )
            )";
        }
        foreach ($specializations as $key => $value) {
            $data = Profile::where('specialization', 'like', '%'.$value['name'].'%');
            if( isset($request->latitude) ){
                $data = $data->whereRaw("{$haversine} < ?", 20);
                
            }
            $count = $data->count();
            
            $specializations[$key]['count'] = $count;
        }
        return $this->sendResponse($specializations, 'Specializations.');
    }

}

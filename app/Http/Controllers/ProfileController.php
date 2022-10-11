<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Profile;
use App\Models\Specialization;
use Redirect;
use Session;

class ProfileController extends Controller
{
   
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function create(Request $request)
    {
        if ($request->isMethod('post')) {
            $input = $request->only('first_name', 'last_name', 'address', 'city', 'state', 'zipcode', 'latitude', 'longitude');
            $result = $this->saveProfile($input);
            $user = User::find(Auth::user()->id);
            if($request ->has('first_name') && $request->has('last_name') ) {
                $user->name = $input['first_name'] . ' ' . $input['last_name'];
                $user->save();
            }

            return response()->json([
                "status" => $result ? false : true,
                "message" => "Profile saved successfully."
            ]);
        }
        return Inertia::render('Profile/CreateProfile');
    }

    public function show(Request $request)
    {
        $user = Auth::user();
        $rating = round($user->average_rating);
        $average_rating = number_format($user->average_rating,1);
        //$userRating = User::with('reviews')->where(['user_type' => 'doctor', 'id' => $id])->first();
        $profile = Profile::withCount('reviews')->where(['user_id' => $user->id])->first();
         if($profile){
            $profile->specialization = explode(',', $profile->specialization);
            $profile->patients_gender = @unserialize($profile->patients_gender);
            $profile->insurance = explode(',', $profile->insurance);
            $profile->other_insurance = (!empty($profile->other_insurance))? explode(',', $profile->other_insurance) :[];
            $profile->tags = explode(',', $profile->tags);
        }
        return Inertia::render('Profile/Profile', ['profile' => $profile, 'user' => $user, 'rating' => $rating, 'average_rating' => $average_rating]);
    }

    public function updateProfile(Request $request)
    {
        
        $input = $request->only('image', 'bio', 'specialize', 'telemedicine', 'insurances', 'other_insurance', 'tags', 'male', 'female', 'other', 'fees');
        $validator = Validator::make($request->all(), [ 
            'bio' => 'required',
            'specialize' => 'required',
            'insurances' => 'required',
            'tags' => 'required',
            'fees' => 'required'
            
        ]);
        if ($validator->fails()) { 
	        	return response(['message'=>$validator->errors()->first(), 'code'=>200], 200);         
	    }
	        
        $input['patients_gender'] = @serialize([
            'male' => @$input['male'],
            'female' => @$input['female'],
            'other' => @$input['other'],
        ]);

        if($request->has('specialize')) {
            $input['specialization'] = implode(',', $input['specialize']);
        }
        if($request->has('insurances')) {
            $input['insurance'] = implode(',', $input['insurances']);
        }
        
        if (in_array("Other", $input['insurances'])){
            $input['other_insurance'] = implode(',', $input['other_insurance']);
        }
        
        if($request->has('tags')) {
            $input['tags'] = implode(',', $input['tags']);
        }

        if($request->image && !empty($request->image) ){            
            $img = $request->image;
            $folderPath = "storage/uploads/images/"; //path location
            
            $image_parts = explode(";base64,", $img);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);
            $uniqid = time();
            $file = $folderPath . $uniqid . '.'.$image_type;
            file_put_contents($file, $image_base64);
            $user = Auth::user();
            $user->profile_photo_path = $file;
            $user->save();
        }


        $result = $this->saveProfile($input);
        return response()->json([
            "status" => $result ? false : true,
            "message" => "Profile updated successfully."
        ]);


    }


    public function bio(Request $request)
    {
        if ($request->isMethod('post')) {
           
            $validator = Validator::make($request->all(), [ 
                'bio' => 'required',
	        ]);

	        if ($validator->fails()) { 
	        	return response(['message'=>$validator->errors()->first(), 'code'=>200], 200);         
	        }
            $input = $request->only('bio');
            $result = $this->saveProfile($input);
            return response()->json([
                "status" => $result ? false : true,
                "message" => "Bio saved successfully."
            ]);
        }
        $user_id = auth()->user()->id;
        $user = User::with('profile')->where('id', $user_id)->first();
        $bio = ($user->profile->bio)?$user->profile->bio:'';
        return Inertia::render('Profile/Bio', ['bio' => $bio]);    }

    public function specialization(Request $request)
    {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [ 
                'specialize' => 'required|',
	        ]);

	        if ($validator->fails()) { 
	        	return response(['message'=>$validator->errors()->first(), 'code'=>200], 200);         
	        }

            $input = $request->only('specialize');

            $input['specialization'] = implode(',', $input['specialize']);
            $result = $this->saveProfile($input);
            return response()->json([
                "status" => $result ? false : true,
                "message" => "Specialization saved successfully."
            ]);
        }

        $specializations = Specialization::all();
        
        $user_id = auth()->user()->id;
        $user = User::with('profile')->where('id', $user_id)->first();
        $selectedSpecialization = (!empty($user->profile->specialization))? explode(',', $user->profile->specialization) :[];

        return Inertia::render('Profile/Specialization', ['specializations' => $specializations, 'selectedSpecialization' => $selectedSpecialization ]);
    }
    
    public function patientsGender(Request $request)
    {
        if ($request->isMethod('post')) {
            $input = $request->only('male','female','other');
            $input['patients_gender'] = @serialize($input);
            $result = $this->saveProfile($input);
            return response()->json([
                 "status" => $result ? false : true,
                "message" => "Gender percentage saved successfully."
            ]);
        }
        $user_id = auth()->user()->id;
        $user = User::with('profile')->where('id', $user_id)->first();
        $patientGender = (!empty($user->profile->patients_gender))? unserialize($user->profile->patients_gender) :[];
        return Inertia::render('Profile/PatientsGender', ['patients_gender' => $patientGender]);
    }

    public function offerTelemedicine(Request $request)
    {
        if ($request->isMethod('post')) {
            $input = $request->only('telemedicine');
            $result = $this->saveProfile($input);
            return response()->json([
                "status" => $result ? false : true,
                "message" => "Telemedicine saved successfully."
            ]);
        }
        $user_id = auth()->user()->id;
        $user = User::with('profile')->where('id', $user_id)->first();
        $telemedicine = (!empty($user->profile->telemedicine))? $user->profile->telemedicine :"";
        return Inertia::render('Profile/OfferTelemedicine', ['telemedicine' => $telemedicine]);
    }

    public function insuranceAcceptance(Request $request)
    {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [ 
                'insurances' => 'required',
	        ]);

	        if ($validator->fails()) { 
	        	return response(['message'=>$validator->errors()->first(), 'code'=>200], 200);         
	        }
            $input = $request->only('insurances', 'other_insurance');
            $input['insurance'] = implode(',', $input['insurances']);
            if (in_array("Other", $input['insurances'])){
                $input['other_insurance'] = implode(',', $input['other_insurance']);
            }
            $result = $this->saveProfile($input);
            return response()->json([
                "status" => $result ? false : true,
                "message" => "Insurances saved successfully."
            ]);
        }

        $user_id = auth()->user()->id;
        $user = User::with('profile')->where('id', $user_id)->first();
        $insurance = (!empty($user->profile->insurance))? explode(',', $user->profile->insurance) :[];
        $other_insurance = (!empty($user->profile->other_insurance))? explode(',', $user->profile->other_insurance) :[];
        return Inertia::render('Profile/InsuranceAcceptance', ['insurance' => $insurance, 'other_insurance' => $other_insurance]);
    }
    
    public function tagsApply(Request $request)
    {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [ 
                'tags' => 'required',
	        ]);

	        if ($validator->fails()) { 
	        	return response(['message'=>$validator->errors()->first(), 'code'=>200], 200);         
	        }
            $input = $request->only('tags');
            $input['tags'] = implode(',', $input['tags']);
            $result = $this->saveProfile($input);
            return response()->json([
                "status" => $result ? false : true,
                "message" => "Tags saved successfully."
            ]);
        }
        $user_id = auth()->user()->id;
        $user = User::with('profile')->where('id', $user_id)->first();
        $tags = (!empty($user->profile->tags))? explode(',', $user->profile->tags) :[];
        return Inertia::render('Profile/TagsApply', ['selectedtags' => $tags]);
    }

    public function saveProfile($input)
    {
        try { 
            $affected = Profile::updateOrCreate(
                ['user_id' =>  Auth::user()->id],
                $input
            );
            return null;
        } catch(\Illuminate\Database\QueryException $ex){ 
          return $ex->getMessage(); 
        }
    }


    public function availability(Request $request)
    {
        $user = Auth::user();
        $profile = Profile::where(['user_id' => $user->id])->first();
        if ($request->isMethod('post')) {
            $input = $request->only('days', 'special_days');
            $input['days'] = serialize($input['days']);
            $input['special_days'] = serialize($input['special_days']);
            $result = $this->saveProfile($input);
            return response()->json([
                "status" => $result ? false : true,
                "message" => "Availability saved successfully."
            ]);
        }
        if($profile) {
            $profile->days = unserialize($profile->days);
            $profile->special_days = unserialize($profile->special_days);
        }
        return Inertia::render('Profile/Availability', ['profile' => $profile, 'user' => $user]);
    }
    
    /**
    * Appointment Fee 
    */
    public function appointmentFee(Request $request){
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [ 
                'fees' => 'required',
	        ]);

	        if ($validator->fails()) { 
	        	return response(['message'=>$validator->errors()->first(), 'code'=>200], 200);         
	        }
            $input = $request->only('fees');
            $input['fees'] = $input['fees'];
            $result = $this->saveProfile($input);
            return response()->json([
                "status" => $result ? false : true,
                "message" => "Appointment fees saved successfully."
            ]);
        }
        $user_id = auth()->user()->id;
        $user = User::with('profile')->where('id', $user_id)->first();
        $fees = (!empty($user->profile->fees))? $user->profile->fees :0;
        return Inertia::render('Profile/Fee', ['fees' => $fees]);
    }

}

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



class ProfileController extends BaseController
{
   
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function location(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'location' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);


        if($validator->fails()){
            return $this->sendResponse('Validation Error.', $validator->errors()->first(), 0);       
        }
        
        $input = $request->only('location');

        $affected = PatientProfile::updateOrCreate(
            ['user_id' =>  Auth::user()->id],
            $input
        );
        
        return $this->sendResponse($input, 'Location updated successfully.');

    }

    public function profile(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'first_name' => 'required',
        //     'last_name' => 'required',
        //     // 'city' => 'required',
        //     // 'state' => 'required',
        //     // 'zip' => 'required',
        //     // 'height' => 'required',
        //     // 'weight' => 'required',
        //     // 'gender' => 'required',
        //     // 'ethnicity' => 'required',
        //     // 'dob' => 'required',
        //     // 'marital_status' => 'required',
        // ]);

        // if($validator->fails()){
        //     return $this->sendResponse('Validation Error.', $validator->errors()->first(), 0);       
        // }
        
        $input = $request->all();
        $input['dob'] = date("Y-m-d", strtotime($input['dob']));

        $affected = PatientProfile::updateOrCreate(
            ['user_id' =>  Auth::user()->id],
            $input
        );
        
        return $this->sendResponse($input, 'Profile updated successfully.');

    }


    public function allergies(Request $request)
    {        
        PatientAllergie::where('user_id', Auth::user()->id)->delete();
        $input = $request->all();
        foreach ($input['allergies'] as $key => $value) {
            $aller = new PatientAllergie();
            $aller->user_id = Auth::user()->id;
            $aller->type = $value['type'];
            $aller->name = $value['name'];
            $aller->comment = $value['comment'];
            $aller->save();
        }
        
        return $this->sendResponse($input, 'Allergies added successfully.');
    }

    public function medicalHistory(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'medical_history' => 'required',
        // ]);

        // if($validator->fails()){
        //     return $this->sendResponse('Validation Error.', $validator->errors()->first(), 0);       
        // }
        
        $input = $request->all();
        
        
        $affected = PatientProfile::updateOrCreate(
            ['user_id' =>  Auth::user()->id],
            $input
        );
        return $this->sendResponse($input, 'Medical History added successfully.');

    }

    public function surgeries(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'surgeries' => 'required',
        //     'year' => 'required',
        //     'age' => 'required',
        // ]);

        // if($validator->fails()){
        //     return $this->sendResponse('Validation Error.', $validator->errors()->first(), 0);       
        // }
        
        $input = $request->all();

        $affected = PatientProfile::updateOrCreate(
            ['user_id' =>  Auth::user()->id],
            $input
        );
        
        return $this->sendResponse($input, 'Surgeries added successfully.');

    }

    public function medications(Request $request)
    {        
        PatientMedication::where('user_id', Auth::user()->id)->delete();
        $input = $request->all();
        
        foreach ($input['medications'] as $key => $value) {
            $aller = new PatientMedication();
            $aller->user_id = Auth::user()->id;
            $aller->name = $value['name'];
            $aller->reason = $value['reason'];
            $aller->save();
        }
        
        return $this->sendResponse($input, 'Medications added successfully.');

    }

    public function sexualHealth(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'sexual_health' => 'required'
        // ]);

        // if($validator->fails()){
        //     return $this->sendResponse('Validation Error.', $validator->errors()->first(), 0);       
        // }
        
        $input = $request->all();

        $affected = PatientProfile::updateOrCreate(
            ['user_id' =>  Auth::user()->id],
            $input
        );
        
        return $this->sendResponse($input, 'Sexual Health added successfully.');

    }

    public function habits(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'habits' => 'required'
        // ]);

        // if($validator->fails()){
        //     return $this->sendResponse('Validation Error.', $validator->errors()->first(), 0);       
        // }
        
        $input = $request->all();

        $affected = PatientProfile::updateOrCreate(
            ['user_id' =>  Auth::user()->id],
            $input
        );
        
        return $this->sendResponse($input, 'Sexual Health added successfully.');

    }

    public function general(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'general' => 'required',
        //     'skin' => 'required',
        //     'eyes' => 'required',
        // ]);

        // if($validator->fails()){
        //     return $this->sendResponse('Validation Error.', $validator->errors()->first(), 0);       
        // }
        
        $input = $request->all();

        $affected = PatientProfile::updateOrCreate(
            ['user_id' =>  Auth::user()->id],
            $input
        );
        
        return $this->sendResponse($input, 'General details added successfully.');

    }

    public function tobacco(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'cigarette' => 'required',
        //     'tobacco' => 'required',
        // ]);

        // if($validator->fails()){
        //     return $this->sendResponse('Validation Error.', $validator->errors()->first(), 0);       
        // }
        
        $input = $request->all();

        $affected = PatientProfile::updateOrCreate(
            ['user_id' =>  Auth::user()->id],
            $input
        );
        
        return $this->sendResponse($input, 'Tobacco details added successfully.');

    }

    public function drug(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'street_drug' => 'required',
        //     'needle_drug' => 'required',
        // ]);

        // if($validator->fails()){
        //     return $this->sendResponse('Validation Error.', $validator->errors()->first(), 0);       
        // }
        
        $input = $request->all();

        $affected = PatientProfile::updateOrCreate(
            ['user_id' =>  Auth::user()->id],
            $input
        );
        
        return $this->sendResponse($input, 'Drug details added successfully.');

    }
    
    public function insurance(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'image' => 'required',
        //     'provider' => 'required',
        //     'phone' => 'required',
        //     'group_number' => 'required',
        // ]);

        // if($validator->fails()){
        //     return $this->sendResponse('Validation Error.', $validator->errors()->first(), 0);       
        // }
        
        $input = $request->all();
        if($request->id){
           $PatientInsurance =  PatientInsurance::find($request->id);
        }else {
            $PatientInsurance =  new PatientInsurance;
        }
        
        $PatientInsurance->provider = $request->provider;
        $PatientInsurance->phone = $request->phone;
        $PatientInsurance->group_number = $request->group_number;
        $PatientInsurance->user_id = Auth::user()->id;
        if($request->has('image')) {
            
            $file = $request->file('image');
            $path = public_path('uploads/insurances/');
            if(@$file) {
                $fileType = $file->getMimeType();
                $fileName = $file->getClientOriginalName();
                $fileExtension = $file->getClientOriginalExtension();
                $fileName = time().'.'. $fileExtension;
                $file->move($path, $fileName); 
                $PatientInsurance->image =  'uploads/insurances/'.$fileName;  
            }
        }
        
        $PatientInsurance->save();
        // $affected = PatientInsurance::createOrUpate(
        //     ['user_id' =>  Auth::user()->id],
        //     $input
        // );
        return $this->sendResponse($PatientInsurance, 'Insurance details added successfully.');

    }
    
    public function insuranceDetails(Request $request)
    {
        $Insurance = PatientInsurance::find($request->id);
        
        return $this->sendResponse($Insurance, 'Insurance Info.');
    }
    


    public function insuranceDelete(Request $request)
    {
        
        
        $PatientInsurance = PatientInsurance::find($request->id);
        if(!empty($PatientInsurance) ){
            $path = public_path('uploads/insurances/').''.$PatientInsurance->image;
            if( file_exists($path)){
                unlink($path);
            }
        }
        $PatientInsurance->delete();
        return $this->sendResponse([], 'Insurance has been deleted successfully.');

    }
    
    public function alcohol(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'drink_alcohol' => 'required',
        //     'how_many' => 'required',
        //     'drinks_in_day' => 'required',
        //     'cut_down' => 'required',
        //     'felt_guilty' => 'required',
        //     'morning_drink' => 'required',
        // ]);

        // if($validator->fails()){
        //     return $this->sendResponse('Validation Error.', $validator->errors()->first(), 0);       
        // }
        
        $input = $request->all();

        $affected = PatientProfile::updateOrCreate(
            ['user_id' =>  Auth::user()->id],
            $input
        );
        
        return $this->sendResponse($input, 'Drug details added successfully.');
    }
    
    /*
    * Get Current User card details
    */
    
    public function cardList(){
        $insurances = PatientInsurance::where('user_id', auth()->user()->id)->where('is_deleted', 0)->get();
        return $this->sendResponse($insurances, '');
    }
    
    /**
     * Get Allergies
     */
     public function GetAllergies(){
        $PatientAllergie = PatientAllergie::where('user_id', auth()->user()->id)->get();
        return $this->sendResponse($PatientAllergie, '');
     }
     
     /**
     * Get Allergies
     */
    public function GetCigarette(){
        $PatientCigarette = PatientProfile::select('cigarette' ,'tobacco')->where('user_id', auth()->user()->id)->first();
        $data = [];
        if($PatientCigarette->cigarette){
            $data = json_decode($PatientCigarette->cigarette);
        }
        return $this->sendResponse(['cigarette' => $data, 'tobacco' => $PatientCigarette->tobacco], '');
    }
    
    /**
     * Get Medication
    */
    public function GetMedication(){
        $PatientMedication = PatientMedication::where('user_id', auth()->user()->id)->get();
         return $this->sendResponse($PatientMedication, '');
    }
    
    /*
     * Delete Insurance
     */
     
    public function DeleteInsurance(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendResponse('Validation Error.', $validator->errors()->first(), 0);       
        }
        $PatientInsurance = PatientInsurance::where('id', $request->id)->first();
        $PatientInsurance->is_deleted = 1;
        $PatientInsurance->save();
        
        return $this->sendResponse($PatientInsurance, 'Patient Insurance has been deleted successfully.');
    }

}

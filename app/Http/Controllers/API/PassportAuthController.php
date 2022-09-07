<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\PatientProfile;
use App\Models\Otp;
// use App\Models\Support;
use Illuminate\Support\Facades\Hash;
use Mail;
// use App\Mail\PasswordResetOtp;
use Stripe;

class PassportAuthController extends BaseController
{
   
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    
    /**
     * Send otp api
     *
     * @return \Illuminate\Http\Response
     */
    public function registerOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'phone' => 'required',
        ]);
   
        if($validator->fails()){
            return $this->sendResponse('Validation Error.', $validator->errors()->first(), 0);       
        }

         // validate mobile number
        if(!preg_match('/^[0-9]{10}+$/', $request->phone)) {
            return $this->sendResponse(null, 'Invalid Mobile Number!', 0);     
        }
        // generate OTP
        $otp_number = random_int(1000, 9999);

        $result = Otp::where(['phone' => $request->phone, 'email' => $request->email, 'active' => 1])->first();

        if($result) {
            return $this->sendResponse(null, 'You are already registered!', 0);  
        }

        $input = ['phone' => $request->phone, 'email' => $request->email, 'active' => 0, 'code' => $otp_number];
        
        $affected = Otp::updateOrCreate(
            ['phone' => $request->phone, 'email' => $request->email],
            $input
        );

        $success['code'] = $otp_number;

        $to = $request->email;
        $subject = "Register OTP";
        $message = '<html><head><title>OTP for Register</title></head><body><div style="font-family:Helvetica,Arial,sans-serif;min-width:1000px;overflow:auto;line-height:2"><div style="margin:50px auto;width:70%;padding:20px 0"><div style="border-bottom:1px solid #eee"><a href="" style="font-size:1.4em;color:#00466a;text-decoration:none;font-weight:600">AirMyMD</a></div><p style="font-size:1.1em">Hi,</p><p>Use the following OTP to complete your Register procedures. OTP is valid for 5 minutes</p><h2 style="background:#00466a;margin:0 auto;width:max-content;padding:0 10px;color:#fff;border-radius:4px">'.$otp_number.'</h2><p style="font-size:.9em">Regards,<br>AirMyMD</p><hr style="border:none;border-top:1px solid #eee"></div></div></body></html>';

        // Always set content-type when sending HTML email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        // More headers
        $headers .= 'From: AirMyMD<admin@smallbizplace.com>' . "\r\n";
        mail($to,$subject,$message,$headers);


        return $this->sendResponse($success, 'OTP sent successfully.');
    }

    /**
     * Verify and Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function registerVerify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required',
        ]);
   
        if($validator->fails()){
            return $this->sendResponse('Validation Error.', $validator->errors()->first(), 0);       
        }

        $result = Otp::where(['code' => $request->code, 'active' => 0])->first();

        if(!$result) {
            return $this->sendResponse(null, 'Invalid Code!', 0);  
        }

        $user = User::where(['email' => $result->email])->first();
        if($user) {
            return $this->sendResponse(null, 'Email adready exists!', 0);  
        }
        

        $input = ['phone' => $result->phone, 'email' => $result->email];
        $input['password'] = bcrypt('airhalo123');
        $input['user_type'] = 'user';
        $user = User::create($input);
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $new = \Stripe\Customer::create([
            'email' => $request->email,
            'name' => "Guest",
            'description' => $request->email." Guest user has been created",
        ]); 
        
        if(isset($new->id)){
            $user->stripe_customer = $new->id;
            $user->save();
        }

        $result->active = 1;
        
        $result->save();
        
        
        

        $input['token'] = $user->createToken('MyAIRMD')->accessToken;

        return $this->sendResponse($input, 'OTP sent successfully.');
    }

    /**
     * Login send OTP api
     *
     * @return \Illuminate\Http\Response
     */
    public function loginOtp(Request $request)
    {
        $user = User::where(['email' => $request->email])->first();
        if(!$user) {
            return $this->sendResponse(null, 'Invalid Email', 0);
        }

        // generate OTP
        $otp_number = random_int(1000, 9999);
        
        $user->remember_token = $otp_number;
        $user->save();

        $success['code'] = $otp_number;

        $to = $request->email;
        $subject = "Login OTP";
        $message = '<html><head><title>OTP for Login</title></head><body><div style="font-family:Helvetica,Arial,sans-serif;min-width:1000px;overflow:auto;line-height:2"><div style="margin:50px auto;width:70%;padding:20px 0"><div style="border-bottom:1px solid #eee"><a href="" style="font-size:1.4em;color:#00466a;text-decoration:none;font-weight:600">AirMyMD</a></div><p style="font-size:1.1em">Hi,</p><p>Use the following OTP to complete your Login procedures. OTP is valid for 5 minutes</p><h2 style="background:#00466a;margin:0 auto;width:max-content;padding:0 10px;color:#fff;border-radius:4px">'.$otp_number.'</h2><p style="font-size:.9em">Regards,<br>AirMyMD</p><hr style="border:none;border-top:1px solid #eee"></div></div></body></html>';

        // Always set content-type when sending HTML email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        // More headers
        $headers .= 'From: AirMyMD<admin@smallbizplace.com>' . "\r\n";
        mail($to,$subject,$message,$headers);

        return $this->sendResponse($success, 'OTP sent successfully.');

    }

    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function loginVerify(Request $request)
    {
        $user = User::where(['remember_token' => $request->remember_token])->first();
        if(!$user) {
            return $this->sendResponse(null, 'Invalid Code', 0);
        }

         // assign user to the session
        Auth::login($user);

        $user = Auth::user(); 
        // $user->device_type = $request->device_type;
        // $user->device_token = $request->device_token;
        $user->remember_token = null;
        $success['token'] =  $user->createToken('MyAIRMD')->accessToken; 
        // $success['email'] =  $user->email;
        // $success['phone'] =  $user->phone;
        // $success['user_type'] =  $user->user_type;
        $user->save();
        $patientProfile = PatientProfile::where('user_id', $user->id)->first();
        if(empty($patientProfile) ){
            PatientProfile::create([
                'user_id' => $user->id
            ]);
        }
        $user = User::with('patientProfile', 'patientAllergie', 'patientMedication')->where('id', $user->id)->get();
        
        $success['userData'] = $user;
        
        
        return $this->sendResponse($success, 'login success');
    }


    // /**
    //  * Register api
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    // public function register(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'email' => 'required|email|unique:users',
    //         'password' => 'required',
    //         'c_password' => 'required|same:password',
    //     ]);
   
    //     if($validator->fails()){
    //         return $this->sendResponse('Validation Error.', $validator->errors()->first(), 0);       
    //     }
   
    //     $input = $request->all();
    //     $input['password'] = bcrypt($input['password']);
    //     $user = User::create($input);
    //     $success['token'] = $user->createToken('MyApp')->accessToken;
    //     $success['name'] =  $user->name;
   
    //     return $this->sendResponse($success, 'User registered successfully.');
    // }

   
    // /**
    //  * Login api
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    // public function login(Request $request)
    // {
    //     $user = User::where(['email' => $request->email])->first();
    //     if(!$user) {
    //         return $this->sendResponse(null, 'invalid email', 0);
    //     }

    //     if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
    //         $user = Auth::user(); 
    //         $user->device_type = $request->device_type;
    //         $user->device_token = $request->device_token;
    //         $success['token'] =  $user->createToken('MyApp')->accessToken; 
    //         $success['name'] =  $user->name;
    //         return $this->sendResponse($success, 'login success');
    //     } 
    //     else{ 
    //         return $this->sendResponse(null, 'invalid password', 0);
    //     } 
    // }

    /**
     * Get User
    */
    public function userInfo() 
    {
        $user = auth()->user();
        return $this->sendResponse($user, 'User Info.');
    }


    public function updateAccount(Request $request)
    {
        $user = User::find(Auth::user()->id);

        if($user) {

            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'phone' => 'required',
            ]);
       
            if($validator->fails()){
                return $this->sendError('Validation Error.',  $validator->errors()->first(), 400);       
            }

            if($user->email != $request->email) {
                $validator = Validator::make($request->all(), [
                    'email' => 'required|email|unique:users',
                ]);
           
                if($validator->fails()){
                    return $this->sendError('Validation Error.',  $validator->errors()->first(), 400);       
                }
            }

            if($request->filled('password') && $request->filled('new_password') ) {
                // The passwords matches
                if (!(Hash::check($request->get('password'), Auth::user()->password))) {
                    return $this->sendError('Validation Error.', ['error' => "Your current password does not matches with the password you provided. Please try again." ], 400);       
                }

                if(strcmp($request->get('password'), $request->get('new_password')) == 0) {
                    //Current password and new password are same
                    return $this->sendError('Validation Error.', ['error' => "New Password cannot be same as your current password. Please choose a different password." ], 400); 
                }
                //Change Password
                $user->password = bcrypt($request->get('new_password'));
            }

            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->save();

            return $this->sendResponse($user, 'Account updated successfully.');

        } else {
          return $this->sendError('User Not Found.', ['error'=>'User Not Found.'], 404);    
        }
    }

    
    public function support(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'reason' => 'required',
            'message' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.',  $validator->errors()->first(), 400);       
        }

        $data = $request->only('name', 'email', 'reason', 'message');
        $data['user_id'] = Auth::user()->id;
        $support = new Support($data);
        $support->save();

        return $this->sendResponse($support, 'Thanks for submitting.');
    }

    /**
     * Returns Authenticated User Details
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetOtp(Request $request)
    {
        if($request->filled('email')) {

            $user = User::where('email', $request->email)->first();
            if($user) {

                $otp = rand(100000, 999999);

                Mail::to($request->email)->send(new PasswordResetOtp($otp, $user));

                User::where('id', $user->id)->update([ "remember_token" => $otp ]);
                
                return $this->sendResponse(["otp" => $otp], 'Email sent successfully.');

            } else {
                return $this->sendError('User Not Found.', ['error'=>'User Not Found.'], 404);    
            }          
        }
        return $this->sendError('Parameter Missing.', ['error'=>'Email is missing in request.'], 400);    
    }


     /**
     * Returns Authenticated User Details
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyOtp(Request $request)
    {
        if($request->filled('otp')) {
            $user = User::where('remember_token', $request->otp)->first();

            if($user) {           
                return $this->sendResponse(["user" => $user->id], 'OTP verified.');    
            } else {
                return $this->sendError('User Not Found.', ['error'=>'User Not Found.'], 404);    
            } 
        }

        return $this->sendError('Parameter Missing.', ['error'=>'OTP is missing in request.'], 400);    
    }


    /**
     * Handles Registration Request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword(Request $request)
    {
    
        if($request->filled('password') && $request->filled('otp')) {

            $user = User::where('remember_token', $request->otp)->first();
            if($user) {
                User::where('id', $user->id)
                    ->update([ "remember_token" => null, 'password' => bcrypt($request->password) ]);
                return $this->sendResponse(["user" => $request->user_id], 'Password changed successfully.');    

            } else {
                return $this->sendError('Wrong details.', ['error'=>'Otp is not valid.'], 404);
            }
        }

        return $this->sendError('Parameter Missing.', ['error'=>'otp and password is missing in request.'], 400);  

    }

    public function notifications(Request $request)
    {
        $user = User::find(Auth::user()->id);
        $user->new_episodes = $request->new_episodes;
        $user->quick_clips = $request->quick_clips;
        $user->save();

        return $this->sendResponse($user, 'Setting updated successfully.');
    }
    

}

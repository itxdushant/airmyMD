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
use App\Models\BankInfo;
use Redirect;
use Session;
use Illuminate\Support\Facades\Hash;


class AccountController extends Controller
{
   
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $stripe = new \Stripe\StripeClient(
            env('STRIPE_SECRET')
        );
    }

    public function show(Request $request)
    {
        $user = Auth::user();
        $profile = Profile::where(['user_id' => $user->id])->first();
         if($profile){
            $profile->specialization = explode(',', $profile->specialization);
            $profile->patients_gender = @unserialize($profile->patients_gender);
            $profile->insurance = explode(',', $profile->insurance);
            $profile->tags = explode(',', $profile->tags);
        }
        $bank  = BankInfo::where('user_id', auth()->user()->id)->first();
        return Inertia::render('Account', ['profile' => $profile, 'user' => $user, 'bankDetails' => $bank]);
    }

    
    public function update(Request $request)
    {
        if ($request->isMethod('post')) {
            $input = $request->only('first_name','last_name','address','city','state','zipcode');
            if(!empty($request->zipcode) ){
                $latlng = $this->get_lat_long($request->zipcode);   
                $input['latitude'] = $latlng['lat'];
                $input['longitude'] = $latlng['long'];
            }
            
            $affected = Profile::updateOrCreate(
                ['user_id' =>  Auth::user()->id],
                $input
            );
            return response()->json([
                "status" => $affected ? true : false,
                "message" => "Account saved successfully."
            ]);
        }
        return response()->json([
            "status" => false
        ]);   
    }
    
    public function get_lat_long($address) {

        $address = str_replace(" ", "+", $address);
        $json = file_get_contents("https://maps.google.com/maps/api/geocode/json?address=$address&sensor=false&key=AIzaSyCbQhq3ry_ZkH73LzIeZP0Y9mVO_kvoasY");
        $json = json_decode($json);

        if(isset($json->results) && !empty($json->results)){

          $lat = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};

          $long = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};

          return ['lat' => $lat, 'long' => $long];

        }else{

          return 0;

        }

    }

    public function updatePassword(Request $request)
    {
        if ($request->isMethod('post')) {
            $input = $request->all();
            $user = User::find(Auth::user()->id);

            if($request->filled('c_password') && $request->filled('n_password') ) {
                if (!(Hash::check($request->get('c_password'), Auth::user()->password))) {
                    return response()->json([
                        "status" => false,
                        "message" => "Your current password does not matches with the password you provided. Please try again."
                    ]);    
                }

                if(strcmp($request->get('c_password'), $request->get('n_password')) == 0) {
                    return response()->json([
                        "status" => false,
                        "message" => "New Password cannot be same as your current password. Please choose a different password."
                    ]);  
                }
                //Change Password
                $user->password = Hash::make($request->get('n_password'));
                $user->save();
                return response()->json([
                    "status" => true,
                    "message" => "Password updated successfully."
                ]);
            }
        }    
        return response()->json([
            "status" => false
        ]);    
    }


    /**
     * Logout function
    */
    public function logout()
    {
        Session::flush();
        
        Auth::logout();

        return redirect('login');
    }

    /**
     * Stripe Account Connect
     */

    public function connectedAccount(Request $request){
        $user = auth()->user();
        $record = BankInfo::where(['user_id' =>  $user->id])->first();
        try {
            if(!$record) {
                $stripe = new \Stripe\StripeClient(
                    env('STRIPE_SECRET')
                );
                $account = $stripe->accounts->create([
                    'type' => 'custom',
                    'country' => 'US',
                    'email' => $user->email,
                    'capabilities' => [
                        'card_payments' => ['requested' => true],
                        'transfers' => ['requested' => true],
                    ],
                ]);
                if(@$account->id) {
                    //save bank account..
                    $info = new BankInfo();
                    $info->user_id = $user->id;
                    $info->account_id = $account->id;
                    $info->status = "pending";
                    $info->payouts_enabled = "pending";
                    $info->save();
                    // create link for account update and send
                    $link = $stripe->accountLinks->create([ 
                        'account' => $account->id,
                        'refresh_url' => 'http://127.0.0.1:8000/banking-info-error',
                        'return_url' => 'http://127.0.0.1:8000/banking-info-success',
                        'type' => 'account_onboarding',
                    ]);
                    return ['url' => $link->url];
                } else {
                    $error = "Error in account create.";
                }
            } else {
                return response()->json([
                    "status" => false,
                    "message" => "Account has already connected with stripe."
                ]);           
            }
        } catch (\Stripe\Exception\RateLimitException $e) {
        // Too many requests made to the API too quickly
            return response()->json([
                "status" => false,
                "message" => $e->getMessage()
            ]);
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            // Invalid parameters were supplied to Stripe's API
            return response()->json([
                "status" => false,
                "message" => $e->getMessage()
            ]);
        } catch (\Stripe\Exception\AuthenticationException $e) {
            // Authentication with Stripe's API failed
            return response()->json([
                "status" => false,
                "message" => $e->getMessage()
            ]);
            // (maybe you changed API keys recently)
        } catch (\Stripe\Exception\ApiConnectionException $e) {
            // Network communication with Stripe failed
            return response()->json([
                "status" => false,
                "message" => $e->getMessage()
            ]);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            // Display a very generic error to the user, and maybe send
            return response()->json([
                "status" => false,
                "message" => $e->getMessage()
            ]);
            // yourself an email
        } catch (Exception $e) {
            // Something else happened, completely unrelated to Stripe
            return response()->json([
                "status" => false,
                "message" => $e->getMessage()
            ]);
        }

        
        
    }

    public function bankingInfoSuccess()
    {
        $user = auth()->user();
        $record = BankInfo::where(['user_id' => $user->id])->first();

        if($record) {
            $stripe = new \Stripe\StripeClient(
                env('STRIPE_SECRET')
            );
            $account = $stripe->accounts->retrieve($record->account_id);
            $status = ''; 
            if($account && $account->payouts_enabled) {
                $status = 'active';              
            } else {
                $status = 'inactive'; 
            }

            BankInfo::where('id', $record->id)->update(["payouts_enabled" => $status]);
            $record = $record->refresh(); 
        }
        return redirect('/account')->with('error', $e->getMessage());
    }

    /**
     * Error Redirection
    */
    public function bankingInfoError()
    {
        redirect('/account')->with('error', 'Try again.');
    }

    /**
     * terms 
     */
    public function terms(){
        return Inertia::render('TermsOfService');
    }

    /**
     * Privacy Policy
     */
    public function privacyPolicy(){
        return Inertia::render('PrivacyPolicy');
    }


    /**
     * Patient Details
    */
    public function patientDetails(Request $request){
        $patient = User::with('patientProfile', 'patientAllergie', 'patientMedication', 'patientInsurance')->where('id', $request->id)->first();
        $patient->patientProfile->medical_history = explode(',', $patient->patientProfile->medical_history);
        $patient->patientProfile->eyes = explode(',', $patient->patientProfile->eyes);
        $patient->patientProfile->tobacco = explode(',', $patient->patientProfile->tobacco);
        return Inertia::render('PatientDetails', ['patient' => $patient]);
    }


    /**
     * 
     * Save Bank
     * 
     */
     
    public function saveBank(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'account_number' => 'required',
            'routing_number' => 'required|min:9',
            'account_holder_name' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendResponse('Validation Error.', $validator->errors()->first(), 0);       
        }
        $user = auth()->user();
        $record = BankInfo::where(['user_id' => $user->id])->first();
        try {
            $stripe = new \Stripe\StripeClient(
                env('STRIPE_SECRET')
            );

            $bank = $stripe->accounts->createExternalAccount(
            $record->account_id,
            [
                'external_account' => [
                    "currency" => "usd",
                    "country" => "us",
                    "object" => "bank_account",
                    "account_holder_name" => $request->account_holder_name,
                    "routing_number" => $request->routing_number,
                    "account_number" => $request->account_number,
                ],
            ]
            ); 

            if($bank) {
                BankInfo::where('id', $record->id)->update([
                    "account_holder_name" => $request->account_holder_name,
                    "routing_number" => $request->routing_number,
                    "account_number" => $request->account_number
                ]);
            }
            return response()->json([
                "status" => true,
                "message" => 'Your bank info added successfully'
            ]);
        } catch (\Stripe\Exception\RateLimitException $e) {
            // Too many requests made to the API too quickly
                return response()->json([
                    "status" => false,
                    "message" => $e->getMessage()
                ]);
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            // Invalid parameters were supplied to Stripe's API
            return response()->json([
                "status" => false,
                "message" => $e->getMessage()
            ]);
        } catch (\Stripe\Exception\AuthenticationException $e) {
            // Authentication with Stripe's API failed
            return response()->json([
                "status" => false,
                "message" => $e->getMessage()
            ]);
            // (maybe you changed API keys recently)
        } catch (\Stripe\Exception\ApiConnectionException $e) {
            // Network communication with Stripe failed
            return response()->json([
                "status" => false,
                "message" => $e->getMessage()
            ]);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            // Display a very generic error to the user, and maybe send
            return response()->json([
                "status" => false,
                "message" => $e->getMessage()
            ]);
            // yourself an email
        } catch (Exception $e) {
            // Something else happened, completely unrelated to Stripe
            return response()->json([
                "status" => false,
                "message" => $e->getMessage()
            ]);
        }
    }
}

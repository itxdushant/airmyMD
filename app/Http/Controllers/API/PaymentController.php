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
use App\Models\PaymentCard;
use Stripe;
use Illuminate\Validation\Rule;



class PaymentController extends BaseController
{
    
    /*
     *
     * Add Card
     *
     */
    public function AddCard(Request $request){
        $data = [];
        
        $validator = Validator::make($request->all(), [
            'card_number' => ['required', Rule::unique('payment_cards')->where(function ($query) use ($request) {
                return $query->where('user_id', auth()->user()->id)->where('is_deleted', 0);
            })]
        ]);

        if($validator->fails()){
            return $this->sendResponse('Validation Error.', $validator->errors()->first(), 0);       
        }
        try {
            Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
            // Use Stripe's library to make requests...
            $card = \Stripe\Token::create(array(
            	'card' => array(
            		'number'	=> $request->card_number,
            		'exp_month'	=> $request->exp_month,
            		'exp_year'	=> $request->exp_year,
            		'cvc'		=> $request->card_cvc,
            	)
            ));
            if ( $card ){
        		$customer = \Stripe\Customer::retrieve( auth()->user()->stripe_customer );
        		
        	    $card = $customer->sources->create(array(
        			'source'	=> $card
        		));
                $customer->default_source = $card->id;
                $customer->save();
        	}
        } catch(\Stripe\Exception\CardException $e) {
            $data['errors'] = $e;
        } catch (\Stripe\Exception\RateLimitException $e) {
            $data['errors'] = $e;
          // Too many requests made to the API too quickly
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            $data['errors'] = $e;
          // Invalid parameters were supplied to Stripe's API
        } catch (\Stripe\Exception\AuthenticationException $e) {
            $data['errors'] = $e;
          // Authentication with Stripe's API failed
          // (maybe you changed API keys recently)
        } catch (\Stripe\Exception\ApiConnectionException $e) {
            $data['errors'] = $e;
          // Network communication with Stripe failed
        } catch (\Stripe\Exception\ApiErrorException $e) {
            $data['errors'] = $e;
          // Display a very generic error to the user, and maybe send
          // yourself an email
        } catch (Exception $e) {
            $data['errors'] = $e;
          // Something else happened, completely unrelated to Stripe
        }
        if(isset($data['errors'])){
            return $this->sendResponse($data, 'Something went wrong please check your card details.', 0);
        }
    	if(empty($data)){
    	    $data = [
               'user_id' => auth()->user()->id,
               'card_number' => $request->card_number,
               'card_name' => $request->card_name,
               'card_date' => $request->exp_month.'/'.$request->exp_year,
               'card_token' => $card->id
            ];
    	    PaymentCard::create($data);   
    	}
    	
        return $this->sendResponse($data, 'Card has been added successfully.');
        
    }
    
    /*
     * Card Listing
     */
     
    public function CardListing(){
        $cardListing = PaymentCard::where('user_id', auth()->user()->id)->where('is_deleted', 0)->get();
        return $this->sendResponse($cardListing, '');
    }
    
    
    /*
     * Card delete
     */
     
    public function DeleteCard(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendResponse('Validation Error.', $validator->errors()->first(), 0);       
        }
        $cardDelete = PaymentCard::where('id', $request->id)->first();
        $cardDelete->is_deleted = 1;
        $cardDelete->save();
        return $this->sendResponse([], 'Card has been deleted successfully.');
    }
       
}
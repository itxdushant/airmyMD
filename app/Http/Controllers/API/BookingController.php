<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Booking;
use App\Models\Profile;
use App\Models\PaymentCard;
use App\Models\Review;
use App\Services\FCMService;
use DateTime;


class BookingController extends BaseController
{
   
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function sendNotificationrToUser($id)
    {
       // get a user to get the fcm_token that already sent.               from mobile apps 
        FCMService::send(
            'cTQsJp-bS-695EsOC2dbdz:APA91bHQegOOVlaDsxHdO9EA-7-Zt677VLQlD3MRkGO3lfhksPaJvC7b6uqQuASCdNPK10UDKAYTofcjF_m0nRNgt29_UHmQdInCutTGmhS8CeLY6_tPZKuFo8CiBkoFVT63mUyJtgmj',
            [
                'title' => 'your title',
                'body' => 'your body',
            ]
        );
    }

    public function booking(Request $request)
    {
                
        $validator = Validator::make($request->all(), [
            'doctor_id' => 'required',
            'booking_date' => 'required',
            'booking_time' => 'required',
            'payment_type' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendResponse('Validation Error.', $validator->errors()->first(), 0);       
        }
        
        $input = $request->all();
        
        $doctor = Profile::where(['user_id' => $input['doctor_id']])->first();
        if(!$doctor) {
            return $this->sendResponse('Request Error.', "Doctor not found", 0);       
        }
        $booking_date = date("Y-m-d", strtotime($input['booking_date']));

        $check_booking = Booking::where(['doctor_id' => $input['doctor_id'], 'booking_date' => $booking_date, 'booking_time' => $input['booking_time'] ])->first();
        if($check_booking) {
            return $this->sendResponse('Request Error.', "This time is already booked.", 0);   
        }

        $booking = new Booking();
        $booking->user_id = Auth::user()->id;
        $booking->booking_date = $booking_date;
        $booking->booking_time = $input['booking_time'];
        $booking->payment_type = $input['payment_type'];        
        $booking->doctor_id = $input['doctor_id'];
        $booking->status = 'pending';
        $booking->reason = @$input['reason'];
        $booking->fees = $doctor->fees;
        if($input['payment_type'] == 'insurance') {
            $validator = Validator::make($request->all(), [
                'source_id' => 'required',
                'source_name' => 'required'            
            ]);

            if($validator->fails()){
                return $this->sendResponse('Validation Error.', $validator->errors()->first(), 0);       
            }
            $booking->source_id = @$input['source_id'];
            $booking->source_name = @$input['source_name'];
        }
        $doctor = User::with('profile')->where('id', $input['doctor_id'])->first();
        $input['doctor_firstname'] = ($doctor->profile)? $doctor->profile->first_name : '';
        $input['doctor_last_name'] = ($doctor->profile)? $doctor->profile->last_name : '';
        if($input['payment_type'] == 'card') {
            $stripe = new \Stripe\StripeClient(
                    env('STRIPE_SECRET')
                );

            $customer = $stripe->customers->retrieve( auth()->user()->stripe_customer );
            $customer->default_source = $request->card_token;
            $customer->save();

            $validator = Validator::make($request->all(), [
                'card_token' => 'required'         
            ]);

            if($validator->fails()){
                return $this->sendResponse('Validation Error.', $validator->errors()->first(), 0);       
            }

            $error = "";
            
            try {
                //stripe client..
                
                $customer = $stripe->customers->retrieve( auth()->user()->stripe_customer );
                $charge = $stripe->charges->create([
                    "amount" => $doctor->profile->fees * 100,
                    "currency" => "usd",
                    "customer" => auth()->user()->stripe_customer,
                    "description" => "AirMyMD" 
                ]);

                $booking->source_id = @$charge['id'];
                $booking->source_name = @$charge['card_name'];
                $booking->save();

                return $this->sendResponse($booking, 'Booking created successfully.');
                 
            } catch (\Stripe\Exception\RateLimitException $e) {
                $error = $e->getMessage();
                $error = $e->getMessage();
            } catch (\Stripe\Exception\InvalidRequestException $e) {
                $error = $e->getMessage();
            } catch (\Stripe\Exception\AuthenticationException $e) {
                $error = $e->getMessage();
            } catch (\Stripe\Exception\ApiConnectionException $e) {
                $error = $e->getMessage();
            } catch (\Stripe\Exception\ApiErrorException $e) {
                $error = $e->getMessage();
            } catch (Exception $e) {
                $error = $e->getMessage();
            }

            return $this->sendResponse('Stripe Error', $error, 0);

            
        }

        $booking->save();
        return $this->sendResponse($input, 'Booking created successfully.');

    }

    public function appointments(Request $request)
    {
        $bookings = Booking::with(['doctor', 'user' => function($query){
            $query->addSelect('id');
         }])->where(['user_id' => Auth::user()->id]);
         
        $bookings = $bookings->get()->toArray();
        $data = [];
        $upcoming = [];
        //$new = [];
        $past = [];
        $today = strtotime(date('Y-m-d'));
        foreach ($bookings as $key => $booking) {
            $bdate = strtotime($booking['booking_date']);
            if($bdate >= $today &&  ($booking['status'] == 'accepted' || $booking['status'] == 'pending') ) {
                array_push($upcoming, $booking);
            } 
            // if($bdate >= $today &&  $booking['status'] == 'pending') {
            //     array_push($new, $booking);
            // }
            if($bdate < $today) {
                array_push($past, $booking);
            }           
        }

        $data['upcoming'] = $upcoming;
        //$data['new'] = $new;
        $data['past'] = $past;
        return $this->sendResponse($data, 'Bookings');
    }

    public function reject(Request $request)
    {
        $booking = Booking::where(['user_id' => Auth::user()->id, 'id' => $request->id])->first();
        $booking->status = 'rejected';
        $booking->save();
        return $this->sendResponse($booking, 'Booking rejected');
    }

    public function accept(Request $request)
    {
        $booking = Booking::where(['user_id' => Auth::user()->id, 'id' => $request->id])->first();
        $booking->status = 'accepted';
        $booking->save();
        return $this->sendResponse($booking, 'Booking accepted');
    }

    public function review(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'doctor_id' => 'required',
            'rating' => 'required',
            'review' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendResponse('Validation Error.', $validator->errors()->first(), 0);       
        }
        
        $input = $request->all();

        $review = new Review();
        $review->user_id = Auth::user()->id; 
        $review->doctor_id = $input['doctor_id']; 
        $review->rating = $input['rating']; 
        $review->review = $input['review']; 
        $review->save();

        return $this->sendResponse($review, 'Review submitted');

    }
    
    /*
    * Booking Slots
    */
    
    public function slots(Request $request){
        $validator = Validator::make($request->all(), [
            'doctor_id' => 'required',
            'booking_date' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendResponse('Validation Error.', $validator->errors()->first(), 0);       
        }
        $doctor = User::with('profile')->where('id', $request->doctor_id)->first();
        $slots = [];
        if($doctor->profile->days){
            $list = [];
            $days = unserialize($doctor->profile->days);
            $dayofweek = date('l', strtotime($request->booking_date));
            if(!empty($days) && is_array($days) ){
                foreach($days as $day){
                    if($day['day'] == $dayofweek && $day['status']){
                        $list = $day;
                    }
                }
            }
            if( !empty($list) ){
                $booking_date = date('Y-m-d', strtotime($request->booking_date));
                $bookingData = Booking::where('doctor_id', $request->doctor_id)
                                        ->where('booking_date', $booking_date)
                                        ->where('user_id',auth()->user()->id)
                                         ->where('status', '!=' , "rejected")
                                        ->pluck('booking_time')->toArray();
                $slots = $this->getTimeSlot(30, $list['start_time'], $list['end_time'], $bookingData);      
            }
        }
        
        $message= '';
        if( empty($slots) ){
            $message = 'Slots are not available on this date';
            $slots['slot_start_time'] = [];
            return $this->sendResponse($slots, $message, 1);
        }
        
        return $this->sendResponse($slots, $message);
        
        // if($doctor->profile->special_days){
        //     $list2 = [];
        //     $special_days = unserialize($doctor->profile->special_days);
        //     //dd($special_days);
        //     if(!empty($special_days) && is_array($special_days) ){
        //         foreach($special_days as $sday){
        //             echo $sday['date'].' --------- '.$request->booking_date;
        //             if($sday['date'] == $request->booking_date){
        //                 $list2 = $sday;
        //             }
        //         }
        //     }
        //     dd($list2);
        // }
    }
    
    public function getTimeSlot($interval, $start_time, $end_time, $bookingData)
    {
        $start = new DateTime($start_time);
        $end = new DateTime($end_time);
        $startTime = $start->format('H:i');
        $endTime = $end->format('H:i');
        $i=0;
        $time = [];
        
        while(strtotime($startTime) <= strtotime($endTime)){
            $start = $startTime;
            $end = date('H:i',strtotime('+'.$interval.' minutes',strtotime($startTime)));
            $startTime = date('H:i',strtotime('+'.$interval.' minutes',strtotime($startTime)));
            $i++;
            $start = date("g:i A", strtotime($start));
            if(strtotime($startTime) <= strtotime($endTime) && !in_array($start, $bookingData )){
                $time['slot_start_time'][] = $start;
                //$time['slot_end_time'][] = $end;
            }
        }
        return $time;
    }

}

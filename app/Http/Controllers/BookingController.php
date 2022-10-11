<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use App\Models\User;
use App\Models\PatientProfile;
use Illuminate\Support\Facades\DB;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Review;
use Redirect;
use Session;


class BookingController extends Controller
{
   
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function sendNotification($token, $title, $messages)
    {
        $SERVER_API_KEY = env('FCM_KEY');
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://fcm.googleapis.com/fcm/send',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>'{
         "to" : "'.$token.'",
         "notification" : {
             "body" : "'.$messages.'",
             "title": "'.$title.'"
         },
         "data" : {
             "body" : "'.$messages.'",
             "title": "'.$title.'"
         }
        }',
          CURLOPT_HTTPHEADER => array(
            'Authorization: key='.$SERVER_API_KEY,
            'Content-Type: application/json'
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
    }

    public function patients(Request $request)
    {
        return Inertia::render('Patients');
    }


    public function patientsList(Request $request)
    {
        $userIds = Booking::with(['user', 'user.patientProfile'])
        ->where('doctor_id',Auth::user()->id)
        ->where(function($query){
            $query->where('status', '=', 'rejected')
                ->orWhere('status', '=', 'completed');
        })->distinct()->pluck('user_id')->toArray();
        
        $users = User::with('patientProfile')->where(['user_type' => 'user'])->whereIn('id', $userIds);

        if($request->filled('keyword')) {
            $keyword = $request->keyword;
            $users->whereHas('patientProfile', function($q) use($keyword) {
                $q->where('first_name', 'like', '%'.$keyword.'%');
            });
        }

        $users = $users->paginate(20);
        return response()->json($users);
    }

    public function appointments(Request $request)
    {
        return Inertia::render('Appointments');
    }

    public function appointmentRequest(Request $request)
    {
        return Inertia::render('AppointmentRequest');
    }

    public function appointmentHistory(Request $request)
    {
        return Inertia::render('AppointmentHistory');
    }


    public function appointmentsList(Request $request)
    {
        $bookings = Booking::with(['user', 'user.patientProfile', 'patientInsurance'])
        ->where(['doctor_id' => Auth::user()->id, 'status' => 'accepted']);
        if($request->filled('keyword')) {
            $keyword = $request->keyword;
            $bookings->whereHas('user.patientProfile', function($q) use($keyword) {
                $q->where(DB::raw("concat(first_name, ' ', last_name)"), 'LIKE', "%".$keyword."%");
            });
        }
        
        if($request->filled('date') ){
            $bookings->where('booking_date', $request->date);
        }

        if(isset($request->order) ){
            $order = $request->order;
            if($request->orderby == 'date'){
                $bookings->orderBy('booking_date', $order);
            }
        }

        $bookings = $bookings->paginate(20);
        return response()->json($bookings);
    }


    public function appointmentsRequest(Request $request)
    {
        $bookings = Booking::with(['user', 'user.patientProfile', 'patientInsurance'])
            ->where(['doctor_id' => Auth::user()->id, 'status' => 'pending']);
        if($request->filled('keyword')) {
            $keyword = $request->keyword;
            $bookings->whereHas('user.patientProfile', function($q) use($keyword) {
                $q->where(DB::raw("concat(first_name, ' ', last_name)"), 'LIKE', "%".$keyword."%");
            });
        }
        if($request->filled('date') ){
            $bookings->where('booking_date', $request->date);
        }

        if(isset($request->order) ){
            $order = $request->order;
            if($request->orderby == 'date'){
                $bookings = $bookings->orderBy("booking_date", $order);
            }
        }

        $bookings = $bookings->paginate(20);
        return response()->json($bookings);
    }

    public function appointmentsHistory(Request $request)
    {
        $bookings = Booking::with(['user','review','user.patientProfile', 'patientInsurance'])
        ->where('doctor_id',Auth::user()->id)
        ->where(function($query){
            $query->where('status', '=', 'rejected')
                ->orWhere('status', '=', 'completed');
        });

        if($request->filled('date') ){
            $bookings->where('booking_date', $request->date);
        }
        if($request->filled('keyword')) {
            $keyword = $request->keyword;
            $bookings->whereHas('user.patientProfile', function($q) use($keyword) {
                $q->where(DB::raw("concat(first_name, ' ', last_name)"), 'LIKE', "%".$keyword."%");
            });
        }
        
        if(isset($request->order) ){
            $order = $request->order;
            if($request->orderby == 'date'){
                $bookings = $bookings->orderBy("booking_date", $order);
            }
        }

        $bookings = $bookings->paginate(20);
        //dd($bookings);
        return response()->json($bookings);
    }



    public function transactions(Request $request)
    {
        return Inertia::render('Transactions');
    }

    public function transactionsList(Request $request)
    {
        $user = Auth::user();
        if($user->user_type == 'admin'){
            $payment = Payment::with('booking', 'booking.user', 'booking.user.patientProfile', 'booking.patientInsurance')->where(['user_id' => Auth::user()->id, 'commission', '!=', 0]);
        }else{
            $payment = Payment::with('booking', 'booking.user', 'booking.user.patientProfile', 'booking.patientInsurance')->where(['doctor_id' => Auth::user()->id, 'commission' => 0]);
        }
        
        //$bookings = Booking::with(['user', 'user.patientProfile', 'patientInsurance'])->where(['doctor_id' => Auth::user()->id]);

        if($request->filled('keyword')) {
            $keyword = $request->keyword;
            $payment->whereHas('booking.user.patientProfile', function($q) use($keyword) {
                $q->where(DB::raw("concat(first_name, ' ', last_name)"), 'LIKE', "%".$keyword."%");
            });
        }

        if($request->filled('date') ){
            $date = $request->date;
            $payment->whereHas('booking', function($q) use($date) {
                $q->where('booking_date', $date);
            });
        }
        $payments = $payment->paginate(20);

        return response()->json($payments);
    }

    public function accept(Request $request)
    {
        $booking = Booking::where(['id' => $request->id])->first();
        
        $booking->status = 'accepted';
        $booking->save();
        
        $user = User::where('id', $booking->user_id)->first();
        if(isset($user->device_token)){
            $this->sendNotification($user->device_token, 'Booking Accepted.', 'Your booking has been accepted by '.(($user->profile->first_name)? $user->profile->first_name:'').' '.(($user->profile->last_name)? $user->profile->last_name:''));
            
            Notification::create([
                'from' => auth()->user()->id,
                'to' => $booking->user_id,
                'is_read' => 0,
                'message' => 'Your booking has been accepted by '.(($user->profile->first_name)? $user->profile->first_name:'').' '.(($user->profile->last_name)? $user->profile->last_name:''),
            ]);
        }
        return response()->json([
            'status' => true,
            'message' => 'Booking accepted.'
        ]);
    }

    public function reject(Request $request)
    {
        $booking = Booking::where(['id' => $request->id])->first();
        $booking->status = 'rejected';
        $booking->save();
        
        $user = User::where('id', $booking->user_id)->first();
        if(isset($user->device_token)){
            $this->sendNotification($user->device_token, 'Booking Rejected.', 'Your booking has been rejected by '.(($user->profile->first_name)? $user->profile->first_name:'').' '.(($user->profile->last_name)? $user->profile->last_name:''));
            Notification::create([
                'from' => auth()->user()->id,
                'to' => $booking->user_id,
                'is_read' => 0,
                'message' => 'Your booking has been rejected by '.(($user->profile->first_name)? $user->profile->first_name:'').' '.(($user->profile->last_name)? $user->profile->last_name:''),
            ]);
        }
        return response()->json([
            'status' => true,
            'message' => 'Booking rejected.'
        ]);
    }

    /**
     * Booking Complete
    */
    public function complete(Request $request)
    {
        $booking = Booking::where(['doctor_id' => Auth::user()->id, 'id' => $request->id])->first();
        $booking->status = 'completed';
        $booking->save();

        $user = User::with('profile')->where('id', $booking->user_id)->first();
        if(isset($user->device_token)){
            $this->sendNotification($user->device_token, 'Appointment Completed.', 'Your appointment has been complet by '.(($user->profile->first_name)? $user->profile->first_name:'').' '.(($user->profile->last_name)? $user->profile->last_name:'') );
            Notification::create([
                'from' => auth()->user()->id,
                'to' => $booking->user_id,
                'is_read' => 0,
                'message' => 'Your appointment has been completed by '.(($user->profile->first_name)? $user->profile->first_name:'').' '.(($user->profile->last_name)? $user->profile->last_name:''),
            ]);
        }
        return response()->json([
            'status' => true,
            'message' => 'Booking completed.'
        ]);
    }

    /**
     * Get Apointments By Date
    */
    public function getApointments(Request $request){
        if(!isset($request->date)){
            $booking_date = date('Y-m-d');
        }else{
            $booking_date = date('Y-m-d', strtotime($request->date));
        }
        $bookings = Booking::with(['user', 'user.patientProfile'])
        ->where(['doctor_id' => Auth::user()->id, 'booking_date' => $booking_date])
        ->latest('id')
        ->limit(6)
        ->get();
        
        if(empty($bookings) ){
            $bookings = null;
        }
        return response()->json([
            'status' => true,
            'appointments' => $bookings
        ]);
    }


    /**
     *get Apointment Count 
     */
    public function  getApointmentCount(Request $request){
        //$currentMonth = date('m');
        $bookings = Booking::selectRaw("booking_date, ".DB::raw('count(*) as total'))->groupBy('booking_date')
            ->where(['doctor_id' => Auth::user()->id])
            //->whereRaw('MONTH(booking_date) = ?',[$currentMonth])
            ->pluck('booking_date', 'total');
        return response()->json([
            'status' => true,
            'appointments' => $bookings
        ]);
    }

    /**
     * Reviews 
     */

    public function reviews(){
        $reviews = Review::with('doctorProfile', 'patientProfile', 'patientProfile.patientProfile')
        ->orderBy('id', 'DESC')
        ->where('doctor_id', auth()->user()->id);
        $reviews = $reviews->paginate(10);
        //dd($reviews);
        return Inertia::render('Reviews', ['reviews' => $reviews]);
    }

    /**
     * 
    */
    public function reviewsList(Request $request){
        $reviews = Review::with('doctorProfile', 'patientProfile', 'patientProfile.patientProfile')
        ->orderBy('id', 'DESC')
        ->offset($request->page)
        ->limit(10)
        ->where('doctor_id', auth()->user()->id);
        $reviews = $reviews->paginate(10);
       // dd($reviews);
       return response()->json($reviews);
    }
}

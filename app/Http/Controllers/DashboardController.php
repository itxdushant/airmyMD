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
use App\Models\Booking;
use App\Models\Message;
use Redirect;
use Session;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(){
        $list= [];
        $booking = Booking::query();
        $booking->select('user_id')->where('doctor_id', auth()->user()->id);
        $list['total_patients'] = $booking->distinct()->count();

        $list['upcoming_booking'] = Booking::whereDate('booking_date', '>', Carbon::now())
                        ->where('doctor_id', auth()->user()->id)
                        ->where(function ($query) {
                            $query->where('status', 'accepted');
                            $query->orWhere('status', 'pending');
                        })->count();
        $total_booking = Booking::where('doctor_id', auth()->user()->id)
                ->count();
        $completed_booking = Booking::where('doctor_id', auth()->user()->id)
                                ->where('status', 'completed')
                                ->count();
        $list['appointment_statistics'] = ($total_booking > 0)? round( ($completed_booking/$total_booking)*100, 2): 0 ;
        
        $earnings = Booking::selectRaw('SUM(fees) as fees')->where('doctor_id', auth()->user()->id)
            ->where('status', 'completed')->first();
        $list['earnings'] = $earnings->fees;
        $user_id = auth()->user()->id;
        $doctor = User::with('profile', 'bank')->where('id', $user_id)->first();
        $list['verify']['availability'] = $doctor->profile->days;
        $list['verify']['bank'] = $doctor->bank;
        $list['latest_msg'] = Message::with('sent.patientProfile')->where(function($query) use ($user_id){
            $query->orWhere('to', $user_id);
        })->latest('id')->limit(6)->get();
        return Inertia::render('Dashboard', ['data' => $list]);
    }
}

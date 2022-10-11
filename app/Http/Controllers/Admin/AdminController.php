<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController as BaseController;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Profile;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
class AdminController extends BaseController
{
    /**
     * User Listing
     */
    public function userList(){
        $doctors = User::with('profile')->where('user_type', 'doctor')->paginate(20);
        return Inertia::render('Admin/Doctors', ['doctors' => $doctors]);
    }

    /**
     * Doctor Approve
    */
    public function doctorApprove(Request $request){
        $doctor = User::find($request->id);
        $doctor->status = 1;
        $doctor->save();
        return response()->json([
            'status' => true,
            'message' => 'Doctor Approved.'
        ]);
    }

    /**
     * Doctor Approve
    */
    public function doctorDisapprove(Request $request){
        $doctor = User::find($request->id);
        $doctor->status = 2;
        $doctor->save();
        return response()->json([
            'status' => true,
            'message' => 'Doctor Disapproved.'
        ]);
    }

    /**
     * Filter Doctors
    */
    public function doctorFilter(Request $request){
        $doctor = User::with('profile')->where('user_type', 'doctor');
        if($request->filled('keyword')) {
            $keyword = $request->keyword;
            $doctor->whereHas('user.profile', function($q) use($keyword) {
                $q->where('first_name', 'like', '%'.$keyword.'%');
                $q->orWhere('last_name', 'like', '%'.$keyword.'%');
            });
        }
        $doctor = $doctor->paginate(5);
        return response()->json($doctor);
    }

    /**
     * Doctor Details
    */
    public function DoctorDetails(Request $request){
        $user = Auth::user();
        $doctor = User::find($request->id);
        $profile = Profile::where(['user_id' => $request->id])->first();
         if($profile){
            $profile->specialization = explode(',', $profile->specialization);
            $profile->patients_gender = @unserialize($profile->patients_gender);
            $profile->insurance = explode(',', $profile->insurance);
            $profile->other_insurance = (!empty($profile->other_insurance))? explode(',', $profile->other_insurance) :[];
            $profile->tags = explode(',', $profile->tags);
        }
        return Inertia::render('Admin/DoctorDetails', ['profile' => $profile, 'user' => $user, 'doctor' => $doctor]);
    }


    public function transactions(Request $request)
    {
        return Inertia::render('Admin/Transactions');
    }

    public function transactionsList(Request $request)
    {
        $user = Auth::user();
        $payment = Payment::with('booking', 'booking.user', 'booking.user.patientProfile', 'booking.patientInsurance')->where('commission',0);
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
                $bookings->where('booking_date', $date);
            });
        }
        $payments = $payment->paginate(20);

        return response()->json($payments);
    }
}


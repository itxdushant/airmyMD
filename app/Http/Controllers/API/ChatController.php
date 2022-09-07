<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\API\BaseController as BaseController;
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

use Illuminate\Support\Facades\Event;

use App\Events\MessageSent;

class ChatController extends BaseController
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }
    
    public function index(Request $request)
    {
        if(Auth::user()->user_type == 'doctor'){
            $userIds = Booking::with(['user', 'user.patientProfile'])
                ->where('doctor_id',Auth::user()->id)
                ->distinct()->pluck('user_id')->toArray();
        }else{
            $userIds = Booking::with(['user', 'user.profile'])
                ->where('user_id',Auth::user()->id) 
                ->distinct()->pluck('doctor_id')->toArray();
        }
        
        $users_list = User::with('patientProfile', 'profile')->whereIn('id', $userIds)->get();
        if(!empty($users_list) ){
            foreach($users_list as $list){
                $unread = Message::where('from', $list->id)
                            ->where('to', auth()->user()->id)
                            ->where('is_read', '0')
                            ->count();
                
                $list->unread = $unread;
                $user_id = auth()->user()->id;
                $list_id = $list->id;
                $list->latest_msg = Message::where(function($query) use ($user_id, $list_id){
                                        $query->where('from', $list_id);
                                        $query->where('to', $user_id);
                                    })->orWhere(function($query) use ($user_id, $list_id){
                                        $query->where('to', $list_id);
                                        $query->where('from', $user_id);
                                    })
                                    ->latest('id')->first();
            }
        }
        //$users_list = $users_list->paginate(20);
        return $this->sendResponse($users_list, 'List Of chat Users.');
    }


    /**
     * Send Messages
     */
    public function sendMessage(Request $request)
    {
        if(isset($request->data['text_msg']) ){
            $user = Auth::user();
            $message =  Message::create([
                            'from' => $user->id,
                            'to' => $request->data['user_id'],
                            'message' => $request->data['text_msg']
                        ]); 
            $current_id =  $user->id;
            $user_id = $request->data['user_id'];

            $message = Message::where(function($query) use ($user_id, $current_id){
                $query->where('from', $current_id);
                $query->where('to', $user_id);
            })->orWhere(function($query) use ($user_id, $current_id){
                $query->where('to', $current_id);
                $query->where('from', $user_id);
            })->latest('id')->paginate(10);
            
            broadcast(new MessageSent($user, $message));
            // $message = $user->messages()->create([
            //     'message' => $request->input('message')
            // ]);
        }
        return ['status' => 'Message Sent!'];
    }

    /**
     * 
     * Get User Messages
     * 
    */
    public function getMessage(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
        ]);
   
        if($validator->fails()){
            return $this->sendResponse('Validation Error.', $validator->errors()->first(), 0);       
        }
        $user = Auth::user();
        $current_id =  $user->id;
        $user_id = $request->user_id;

        $message = Message::groupBy('created_at')->where(function($query) use ($user_id, $current_id){
            $query->where('from', $current_id);
            $query->where('to', $user_id);
        })->orWhere(function($query) use ($user_id, $current_id){
            $query->where('to', $current_id);
            $query->where('from', $user_id);
        })->latest('id')->paginate(10);
        return response()->json($message);
    }

    
}
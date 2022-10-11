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
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Event;

use App\Events\MessageSent;

class ChatController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }
    
    public function index( $id=0)
    {
        $to = Message::where('from', Auth::user()->id)->distinct()->pluck('to')->toArray();
        $from = Message::where('to', Auth::user()->id)->distinct()->pluck('from')->toArray();
        $userIds = array_merge($to, $from);
        if($id){
            if(!in_array((int)$id, $userIds)){
                $userIds = array_merge([(int)$id], $userIds);       
            }
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
        $doctor = User::with('profile')->where('id', auth()->user()->id)->first();
        return Inertia::render('Chat', [ 'conversion'=> $users_list, 'user_id' => auth()->user()->id, 'selected_id' => (int)$id, 'doctor' => $doctor]);
    }


    /**
     * Send Messages
     */
    public function sendMessage(Request $request)
    {
        
        if(isset($request->text_msg) || isset($request->images) ){
            $user = Auth::user();
            $imageData = [];
            //dd($request->all());
            if(isset($request->images)){

                if($request->has('images') && count($request->images)) {
                    $files = $request->images;
                    $path = public_path('uploads/media');           
                    foreach ($files as $key => $file) {
                        //$img = str_replace('data:image/png;base64,', '', $file);
                        $base64 = explode(';base64,', $file);
                        $img = str_replace(' ', '+', $base64[1]);
                        $data = base64_decode($img);
                        $extension = explode('/', $base64[0]);
                        $filename = rand(10,100).time(). '.'.$extension[1];
                        $fileData = $path."/" . $filename;
                        if(file_put_contents($fileData, $data) ) {
                            array_push($imageData, $filename);
                        }
                    }
                }
            }
            $requestData = [
                'from' => $user->id,
                'to' => $request->user_id
            ];
            if(!empty($request->text_msg) ){
                $requestData['message'] = $request->text_msg;
            }
            if(!empty($imageData) ){
                $requestData['media'] = json_encode($imageData);
            }
            $message =  Message::create($requestData); 
            
            $user = Auth::user();
            $current_id =  $user->id;
            $user_id = $request->user_id;
            $message = Message::selectRaw("*, DATE_FORMAT(created_at, '%Y-%m-%e') date")->orderBy('date', 'DESC')
            ->where(function($query) use ($user_id, $current_id){
                $query->where('from', $current_id);
                $query->where('to', $user_id);
            })->orWhere(function($query) use ($user_id, $current_id){
                $query->where('to', $current_id);
                $query->where('from', $user_id);
            })->orderBy('created_at', 'ASC')->simplePaginate(20); 
            $message = $message->groupBy('date')->reverse();
            
            broadcast(new MessageSent($$user_id, ['message' => $message], $request->text_msg));
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
        //Message::where('to', auth()->user()->id)->where('from', $user_id);
        $offset = (isset($request->page) && $request->page>0)? $request->page: 0;
        $message = Message::selectRaw("*, DATE_FORMAT(created_at, '%Y-%m-%e') date")->orderBy('date', 'DESC')->where(function($query) use ($user_id, $current_id){
            $query->where('from', $current_id);
            $query->where('to', $user_id);
        })->orWhere(function($query) use ($user_id, $current_id){
            $query->where('to', $current_id);
            $query->where('from', $user_id);
        })->paginate(20); 
        $message = $message->groupBy('date')->reverse();
        return response()->json($message);
    }


    public function searchPatient( Request $request)
    {
        $to = Message::where('from', Auth::user()->id)->distinct()->pluck('to')->toArray();
        $from = Message::where('to', Auth::user()->id)->distinct()->pluck('from')->toArray();
        $userIds = array_merge($to, $from);
        
        $users_list = User::with('patientProfile', 'profile')->whereIn('id', $userIds);
        if($request->filled('keyword')) {
            $keyword = $request->keyword;
            $users_list->whereHas('patientProfile', function($q) use($keyword) {
                $q->where(DB::raw("concat(first_name, ' ', last_name)"), 'LIKE', "%".$keyword."%");
            });

        }
        $users_list = $users_list->get();
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
        $data = [ 'conversion'=> $users_list, 'user_id' => auth()->user()->id];
        return response()->json($data);
        //$users_list = $users_list->paginate(20);
        //return Inertia::render('Chat', [ 'conversion'=> $users_list, 'user_id' => auth()->user()->id, 'selected_id' => (int)$id]);
    }

    
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use App\Models\Clip;
use App\Models\DocumentaryType;
use App\Models\Documentary;
use Redirect;
use Session;


class DocumentaryController extends Controller
{
   
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function index()
    {
        $clips = Clip::all();
        return Inertia::render('Documentary/Index', ['clips' => $clips]);
    }

    public function store(Request $request)
    {

        $time = time();
        
        if($request->has('image')) { 

            $name = $time.'.'.$request->image->extension();  
            $path = Storage::disk('s3')->put('documentary/images', $request->image);

            $docum = new Documentary();
            $docum->title = $request->title;
            $docum->image = $path;
            $docum->city = $request->city;
            $docum->state = $request->state;
            $docum->genre = $request->genre;
            $docum->release_date = $request->release_date;
            $docum->save(); 

            return response()->json([
                    "status" => true,
                    "message" => 'Documentary uploaded successfully.',
                    "id" => @$docum->id,
                ], 200); 

        } else {
            return response()->json([
                    "status" =>  false,
                    "message" => 'Please fill all fields.',
                ], 200); 
        }
 
    }


    public function storeType(Request $request)
    {

        $time = time();
        $image_path = "";
        $video_path = "";
        if($request->has('image')) { 
            $image_path = Storage::disk('s3')->put('documentary/'.$request->bucket, $request->image);
        }
        if($request->has('video')) { 
            $video_path = Storage::disk('s3')->put('documentary/'.$request->bucket, $request->video);
        }

        $docum = new DocumentaryType();
        $docum->documentary_id = $request->doc_id;
        $docum->type = $request->type;
        $docum->image = $image_path;
        $docum->video = $video_path;
        $docum->hours = $request->hours;
        $docum->minutes = $request->minutes;
        $docum->description = $request->description;
        $docum->save(); 

        return response()->json([
            "status" => true,
            "message" => ucfirst(str_replace('-', ' ', $request->type)) .' uploaded successfully.',
        ], 200); 

    }

    public function storeJulia(Request $request)
    {

        $time = time();
        $image_path = "";
        if($request->has('image')) { 
            $image_path = Storage::disk('s3')->put('documentary/'.$request->bucket, $request->image);
        }

        $docum = new DocumentaryType();
        $docum->documentary_id = $request->doc_id;
        $docum->type = $request->type;
        $docum->image = $image_path;
        $docum->description = $request->description;
        $docum->save(); 

        return response()->json([
            "status" => true,
            "message" => ucfirst(str_replace('-', ' ', $request->type)) .' uploaded successfully.',
        ], 200); 

    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    
    public function getMediaAttribute($value)
    {
        $media = [];
        if($value){
            $data = json_decode($value);
            if(!empty($data) && is_array($data) ){
                foreach($data as $val){
                    $media[] = env('APP_URL').'/uploads/media/'.$val;
                }
            }
        }
        
        return ($media)? (object) $media: null;
    }

    public function sent()
    {
        return $this->hasOne(user::class, 'id', 'from');
    }

    public function received()
    {
        return $this->hasOne(user::class, 'id', 'to');
    }
}

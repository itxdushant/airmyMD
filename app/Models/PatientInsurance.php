<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientInsurance extends Model
{
    
    use HasFactory;
    
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'image',
        'provider',
        'phone',
        'group_number',
    ];



    public function getImageAttribute($value)
    {
        return env('APP_URL').'/'.$value;
    }
}

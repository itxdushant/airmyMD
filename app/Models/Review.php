<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
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
        'doctor_id',
        'rating',
        'review'
    ];

    public function doctorProfile()
    {
        return $this->hasOne(User::class,'id', 'doctor_id');
    }

    public function patientProfile()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

}
